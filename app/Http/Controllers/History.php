<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class History extends BaseController
{
    public function index()
    {
        $module = 'Data Histori Peminjaman';
        return view('admin.histori.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Peminjaman::where('status', 'Kembali')->get();

        $combinedData = $dataFull->map(function ($item) {
            $user = User::where('uuid', $item->uuid_anggota)->first();
            $buku = Buku::where('uuid', $item->uuid_buku)->first();

            $item->peminjam = $user->name;
            $item->nim = $user->username;
            $item->jurusan = $user->jurusan;
            $item->buku = $buku->judul;

            // Menghitung tanggal pengembalian dengan menambahkan 7 hari ke tanggal pinjam
            $item->tanggal_pengembalian = Carbon::parse($item->tanggal_pinjam)->addDays(7)->format('d-m-Y');

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }
}
