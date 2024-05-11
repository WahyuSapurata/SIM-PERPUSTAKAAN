<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEbookRequest;
use App\Http\Requests\UpdateEbookRequest;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EbookController extends BaseController
{
    public function index()
    {
        $module = 'Data E-Book';
        return view('admin.ebook.index', compact('module'));
    }

    public function add()
    {
        $module = 'Tambah E-Book';
        return view('admin.ebook.tambah', compact('module'));
    }

    public function edit($params)
    {
        $module = 'Edit E-Book';
        $this->show($params);
        return view('admin.ebook.edit', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Ebook::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreEbookRequest $storeEbookRequest)
    {
        $data = array();

        $newFoto = '';
        $newFile = '';
        if ($storeEbookRequest->file('foto')) {
            $extension = $storeEbookRequest->file('foto')->extension();
            $newFoto = $storeEbookRequest->judul . '-foto-' . now()->timestamp . '.' . $extension;
            $storeEbookRequest->file('foto')->storeAs('foto_ebook', $newFoto);
        }
        if ($storeEbookRequest->file('file')) {
            $extension = $storeEbookRequest->file('file')->extension();
            $newFile = $storeEbookRequest->judul . '-file-' . now()->timestamp . '.' . $extension;
            $storeEbookRequest->file('file')->storeAs('file_ebook', $newFile);
        }

        try {
            $data = new Ebook();
            $data->judul = $storeEbookRequest->judul;
            $data->sinopsis = $storeEbookRequest->sinopsis;
            $data->pengarang = $storeEbookRequest->pengarang;
            $data->foto = $newFoto;
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
            $data = Ebook::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreEbookRequest $storeEbookRequest, $params)
    {
        $data = Ebook::where('uuid', $params)->first();
        // Simpan nama foto lama untuk dihapus

        $oldFotoPath = public_path('foto_ebook/' . $data->foto);
        $oldFilePath = public_path('file_ebook/' . $data->file);

        $newFoto = '';
        $newFile = '';
        if ($storeEbookRequest->file('foto')) {
            $extension = $storeEbookRequest->file('foto')->extension();
            $newFoto = $storeEbookRequest->judul . '-foto-' . now()->timestamp . '.' . $extension;
            $storeEbookRequest->file('foto')->storeAs('foto_ebook', $newFoto);

            // Hapus foto lama jika ada
            if (File::exists($oldFotoPath)) {
                File::delete($oldFotoPath);
            }
        }
        if ($storeEbookRequest->file('file')) {
            $extension = $storeEbookRequest->file('file')->extension();
            $newFile = $storeEbookRequest->judul . '-file-' . now()->timestamp . '.' . $extension;
            $storeEbookRequest->file('file')->storeAs('file_ebook', $newFile);

            // Hapus foto lama jika ada
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
        }

        try {
            $data->judul = $storeEbookRequest->judul;
            $data->sinopsis = $storeEbookRequest->sinopsis;
            $data->pengarang = $storeEbookRequest->pengarang;
            $data->foto = $storeEbookRequest->file('foto') ? $newFoto : $data->foto;
            $data->file = $storeEbookRequest->file('file') ? $newFile : $data->file;
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
            $data = Ebook::where('uuid', $params)->first();
            // Simpan nama foto lama untuk dihapus
            $oldFotoPath = public_path('foto_ebook/' . $data->foto);
            $oldFilePath = public_path('file_ebook/' . $data->file);
            // Hapus foto lama jika ada
            if (File::exists($oldFotoPath)) {
                File::delete($oldFotoPath);
            }
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
            $result = Ebook::where('judul', 'like', "%$keyword%")->get();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($result, 'Show data success');
    }
}
