<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BukuController extends BaseController
{
    public function index()
    {
        $module = 'Data Buku';
        return view('admin.buku.index', compact('module'));
    }

    public function add()
    {
        $module = 'Tambah Buku';
        return view('admin.buku.tambah', compact('module'));
    }

    public function edit($params)
    {
        $module = 'Edit Buku';
        $this->show($params);
        return view('admin.buku.edit', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Buku::all();

        $combinedData = $dataFull->map(function ($item) {
            $kategori = KategoriBuku::where('uuid', $item->uuid_kategori)->first();
            $peminjam = Peminjaman::where('uuid_buku', $item->uuid)->where('status', 'Terpinjam')->count();

            $item->kategori = $kategori->nama_kategori;
            $item->jumlah_stok = $item->stok - $peminjam;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function getByUuid($params)
    {
        // Mengambil semua data pengguna
        $dataFull = Buku::where('uuid_kategori', $params)->get();

        $combinedData = $dataFull->map(function ($item) {
            $kategori = KategoriBuku::where('uuid', $item->uuid_kategori)->first();
            $peminjam = Peminjaman::where('uuid_buku', $item->uuid)->where('status', 'Terpinjam')->count();

            $item->kategori = $kategori->nama_kategori;
            $item->jumlah_stok = $item->stok - $peminjam;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function store(StoreBukuRequest $storeBukuRequest)
    {
        $data = array();

        $newFoto = '';
        if ($storeBukuRequest->file('foto')) {
            $extension = $storeBukuRequest->file('foto')->extension();
            $newFoto = $storeBukuRequest->judul . '-' . now()->timestamp . '.' . $extension;
            $storeBukuRequest->file('foto')->storeAs('foto_buku', $newFoto);
        }

        try {
            $data = new Buku();
            $data->uuid_kategori = $storeBukuRequest->uuid_kategori;
            $data->judul = $storeBukuRequest->judul;
            $data->sinopsis = $storeBukuRequest->sinopsis;
            $data->pengarang = $storeBukuRequest->pengarang;
            $data->tahun_terbit = $storeBukuRequest->tahun_terbit;
            $data->penerbit = $storeBukuRequest->penerbit;
            $data->lokasi = $storeBukuRequest->lokasi;
            $data->stok = $storeBukuRequest->stok;
            $data->foto = $newFoto;
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
            $data = Buku::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreBukuRequest $storeBukuRequest, $params)
    {
        $data = Buku::where('uuid', $params)->first();
        // Simpan nama foto lama untuk dihapus

        $oldFotoPath = public_path('foto_buku/' . $data->foto);

        $newFoto = '';
        if ($storeBukuRequest->file('foto')) {
            $extension = $storeBukuRequest->file('foto')->extension();
            $newFoto = $storeBukuRequest->judul . '-' . now()->timestamp . '.' . $extension;
            $storeBukuRequest->file('foto')->storeAs('foto_buku', $newFoto);

            // Hapus foto lama jika ada
            if (File::exists($oldFotoPath)) {
                File::delete($oldFotoPath);
            }
        }

        try {
            $data->uuid_kategori = $storeBukuRequest->uuid_kategori;
            $data->judul = $storeBukuRequest->judul;
            $data->sinopsis = $storeBukuRequest->sinopsis;
            $data->pengarang = $storeBukuRequest->pengarang;
            $data->tahun_terbit = $storeBukuRequest->tahun_terbit;
            $data->penerbit = $storeBukuRequest->penerbit;
            $data->lokasi = $storeBukuRequest->lokasi;
            $data->stok = $storeBukuRequest->stok;
            $data->foto = $storeBukuRequest->file('foto') ? $newFoto : $data->foto;
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
            $data = Buku::where('uuid', $params)->first();
            // Simpan nama foto lama untuk dihapus
            $oldFotoPath = public_path('foto_buku/' . $data->foto);
            // Hapus foto lama jika ada
            if (File::exists($oldFotoPath)) {
                File::delete($oldFotoPath);
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
            $result = Buku::where('judul', 'like', '%' . $keyword . '%')->get();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($result, 'Show data success');
    }
}
