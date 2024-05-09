<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriBukuRequest;
use App\Http\Requests\UpdateKategoriBukuRequest;
use App\Models\KategoriBuku;

class KategoriBukuController extends BaseController
{
    public function index()
    {
        $module = 'Data Kategori';
        return view('admin.kategori.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = KategoriBuku::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreKategoriBukuRequest $storeKategoriBukuRequest)
    {
        $data = array();
        try {
            $data = new KategoriBuku();
            $data->nama_kategori = $storeKategoriBukuRequest->nama_kategori;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = KategoriBuku::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreKategoriBukuRequest $storeKategoriBukuRequest, $params)
    {
        try {
            $data = KategoriBuku::where('uuid', $params)->first();
            $data->nama_kategori = $storeKategoriBukuRequest->nama_kategori;
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
            $data = KategoriBuku::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
