<?php

namespace App\Http\Controllers;

use App\Http\Requests\UbahPassword as RequestsUbahPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UbahPassword extends BaseController
{
    public function index()
    {
        $module = 'Ubah Password';
        $akun = auth()->user();
        return view('admin.ubahpassword.index', compact('module', 'akun'));
    }

    public function update(RequestsUbahPassword $ubahPassword, $params)
    {
        if ($ubahPassword->password_lama && Hash::check($ubahPassword->password_lama, auth()->user()->password) == false) {
            return $this->sendError('Invalid input', 'Password lama tidak sesuai', 200);
        }

        try {
            $data = User::where('uuid', $params)->first();
            $data->name = $ubahPassword->name;
            $data->username = $ubahPassword->username;
            $data->password = $ubahPassword->password ? $ubahPassword->password : $data->password;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
