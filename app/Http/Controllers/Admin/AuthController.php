<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            // Login
            if (Auth::guard('admin')->attempt(['email' => request('email'),
                'password' => request('password')])) {
                return redirect()->route('admin.subscribers.index')
                    ->with('success', 'Logged In Successfully');
            } else {
                return redirect()->route('admin.login')->with('error', 'Bad credentials');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Error');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('admin.login')->with('success', 'Logged Out Successfully');
    }
}
