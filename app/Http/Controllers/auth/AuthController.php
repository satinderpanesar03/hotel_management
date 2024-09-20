<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function loginForm(){
        return view('admin.auth.login');
    }

    public function login(Request $request){
        return $this->authService->authenticate($request);
    }
}
