<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\EmailExistsForRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Exception;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', new EmailExistsForRole(2)],
                'password' => 'required|min:8'
            ]);

            if ($validator->fails()) {
                return $this->response(false, $validator->errors()->first(), [], 422);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('user-token')->plainTextToken;

                return $this->response(true, 'User successfully logged in', [
                    'token' => $token,
                    'email' => $user->email,
                ], 200);
            }

            return $this->response(false, 'Unauthorized: Invalid email or password.', [], 401);
        } catch (Exception $e) {

            return $this->response(false, 'An unexpected error occurred. Please try again later.', [$e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->response(false, $validator->errors()->first(), [], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('user-token')->plainTextToken;

            return $this->response(true, 'User successfully registered', [
                'token' => $token,
                'email' => $user->email,
            ], 201);
        } catch (Exception $e) {
            return $this->response(false, 'An unexpected error occurred. Please try again later.', [], 500);
        }
    }
}
