<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Anggota extends BaseController
{
    public function index()
    {
        $module = 'Anggota';
        return view('admin.anggota.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = User::where('role', 'mahasiswa')->get();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update($params)
    {
        try {
            $data = User::where('uuid', $params)->first();
            $data->status = "Aktiv";
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = User::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
