<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth as RequestsAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class Auth extends BaseController
{
    // web

    public function show()
    {
        return view('auth.login');
    }

    public function login_proses(RequestsAuth $authRequest)
    {
        $credential = $authRequest->getCredentials();

        if (!FacadesAuth::attempt($credential)) {
            return redirect()->route('login.login-akun')->with('failed', 'Username atau Password salah')->withInput($authRequest->only('username'));
        } else {
            return $this->authenticated();
        }
    }

    public function authenticated()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard-admin');
        }
    }

    public function logout()
    {
        FacadesAuth::logout();
        return redirect()->route('login.login-akun')->with('success', 'Berhasil Logout');
    }

    // api

    // public function do_login(RequestsAuth $authRequest)
    // {
    //     $credential = $authRequest->getCredentials();
    //     if (FacadesAuth::attempt($credential)) {
    //         $token = $authRequest->user()->createToken('tokenAPI')->plainTextToken;

    //         return $this->sendResponse($token, 'Berhasil login.');
    //     } else {
    //         return $this->sendError('Unauthorised.', ['error' => 'Username atau Password'], 401);
    //     }
    // }

    // public function revoke(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return $this->sendResponse('Success', 'Berhasil logout');
    // }

    // public function get_user()
    // {
    //     $user = auth()->user();
    //     return $this->sendResponse($user, 'Berhasil get data');
    // }
}
