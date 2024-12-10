<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends BaseController
{
    public function index()
    {
        $module = 'Data Peminjaman';
        return view('admin.peminjaman.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Peminjaman::where('status', 'Terpinjam')->get();

        $combinedData = $dataFull->map(function ($item) {
            $user = User::where('uuid', $item->uuid_anggota)->first();
            $buku = Buku::where('uuid', $item->uuid_buku)->first();

            $item->peminjam = $user->name;
            $item->nim = $user->username;
            $item->jurusan = $user->jurusan;
            $item->buku = $buku->judul;
            $item->lokasi = $buku->lokasi;

            // Menghitung tanggal pengembalian dengan menambahkan 7 hari ke tanggal pinjam
            $pengembalian = Carbon::parse($item->tanggal_pinjam)->addDays(7)->format('d-m-Y');
            $item->tanggal_pengembalian = $pengembalian;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function store(Request $request)
    {
        $data = array();
        $tanggalPeminjaman = Carbon::now();
        try {
            $data = new Peminjaman();
            $data->uuid_anggota = $request->uuid_anggota;
            $data->uuid_buku = $request->uuid_buku;
            $data->tanggal_pinjam = $tanggalPeminjaman->format('d-m-Y');
            $data->status = "Terpinjam";
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
            $data = Peminjaman::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(Request $request, $params)
    {
        try {
            $data = Peminjaman::where('uuid', $params)->first();
            $data->status = "Kembali";
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
            $data = Peminjaman::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
