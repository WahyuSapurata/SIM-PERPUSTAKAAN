<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth as RequestsAuth;
use App\Http\Requests\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

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

    public function register(Register $register)
    {
        $data = array();
        try {
            $data = new User();
            $data->name = $register->name;
            $data->username = $register->username;
            $data->email = $register->email;
            $data->jurusan = $register->jurusan;
            $data->password = Hash::make($register->password);
            $data->role = 'mahasiswa';
            $data->status = "Belum Aktiv";
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Register data success');
    }

    public function do_login(RequestsAuth $authRequest)
    {
        $user = User::where('username', $authRequest->username)->first();
        if ($user->status == "Belum Aktiv") {
            return $this->sendError('Unauthorised.', ['error' => 'Akun belum di verifikasi Admin'], 401);
        }

        $credential = $authRequest->getCredentials();
        if (FacadesAuth::attempt($credential)) {
            $token = $authRequest->user()->createToken('tokenAPI')->plainTextToken;
            $data = [
                'token' => $token,
                'user' => $user
            ];

            return $this->sendResponse($data, 'Berhasil login.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Username atau Password Salah'], 401);
        }
    }

    public function revoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse('Success', 'Berhasil logout');
    }

    // public function get_user()
    // {
    //     $user = auth()->user();
    //     return $this->sendResponse($user, 'Berhasil get data');
    // }
}
