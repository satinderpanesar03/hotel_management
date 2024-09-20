<?php

namespace App\Services;

use App\Rules\EmailExistsForRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AuthService
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', new EmailExistsForRole(1)],
            'password' => 'required',
        ], [
            'email.exists' => 'Email does not exist'
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return Redirect::route('admin.login');
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            flash()->error(ucfirst('invalid password'));
            return Redirect::route('admin.login');
        }

        Session::put('user', Auth::user());

        flash()->success(ucfirst('login successfully'));
        return Redirect::intended('admin/dashboard');
    }
}
