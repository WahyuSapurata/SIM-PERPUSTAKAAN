<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkripsiRequest;
use App\Http\Requests\UpdateSkripsiRequest;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SkripsiController extends BaseController
{
    public function index()
    {
        $module = 'Data Skripsi';
        return view('admin.skripsi.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Skripsi::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreSkripsiRequest $storeSkripsiRequest)
    {
        $data = array();

        $newFile = '';
        if ($storeSkripsiRequest->file('file')) {
            $extension = $storeSkripsiRequest->file('file')->extension();
            $newFile = 'skripsi' . '-file-' . now()->timestamp . '.' . $extension;
            $storeSkripsiRequest->file('file')->storeAs('skripsi', $newFile);
        }

        try {
            $data = new Skripsi();
            $data->judul = $storeSkripsiRequest->judul;
            $data->abstrak = $storeSkripsiRequest->abstrak;
            $data->file = $newFile;
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
            $data = Skripsi::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreSkripsiRequest $storeSkripsiRequest, $params)
    {
        $data = Skripsi::where('uuid', $params)->first();
        // Simpan nama foto lama untuk dihapus

        $oldFilePath = public_path('skripsi/' . $data->file);

        $newFile = '';
        if ($storeSkripsiRequest->file('file')) {
            $extension = $storeSkripsiRequest->file('file')->extension();
            $newFile = 'skripsi' . '-file-' . now()->timestamp . '.' . $extension;
            $storeSkripsiRequest->file('file')->storeAs('skripsi', $newFile);

            // Hapus foto lama jika ada
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
        }

        try {
            $data->judul = $storeSkripsiRequest->judul;
            $data->abstrak = $storeSkripsiRequest->abstrak;
            $data->file = $storeSkripsiRequest->file('file') ? $newFile : $data->file;
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
            $data = Skripsi::where('uuid', $params)->first();
            // Simpan nama foto lama untuk dihapus
            $oldFilePath = public_path('skripsi/' . $data->file);
            // Hapus foto lama jika ada
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');

            // Cari buku berdasarkan judul atau penulis
            $result = Skripsi::where('judul', 'like', '%' . $keyword . '%')->get();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($result, 'Show data success');
    }
}
