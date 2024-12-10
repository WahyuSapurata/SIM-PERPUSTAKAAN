<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Denda extends BaseController
{
    public function index()
    {
        $module = 'Data Denda';
        return view('admin.denda.index', compact('module'));
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

            // Menghitung tanggal pengembalian dengan menambahkan 7 hari ke tanggal pinjam
            $tanggalPengembalian = Carbon::parse($item->tanggal_pinjam)->addDays(7);
            // $tanggalNow = Carbon::now()->format('Y-m-d');
            $tanggalNow = Carbon::now()->format('Y-m-d');

            // Menghitung selisih hari
            $selisihHari = $tanggalPengembalian->diffInDays($tanggalNow);

            // Menghitung denda
            $biayaDendaPerHari = 1000; // Misalnya Rp. 1000 per hari
            $denda = $selisihHari * $biayaDendaPerHari;

            // Menambahkan informasi denda ke dalam item
            $item->tanggal_pengembalian = $tanggalPengembalian->format('d-m-Y');
            $item->denda = $denda;
            $item->hari = $selisihHari;

            return $item;
        });

        // Mengambil data peminjaman yang tanggal pengembaliannya telah lewat
        $filteredData = $combinedData->filter(function ($item) {
            return $item->tanggal_pengembalian < Carbon::now()->format('d-m-Y');
        });

        // Mengembalikan response berdasarkan filteredData yang sudah disaring
        return $this->sendResponse($filteredData, 'Get data success');
    }
}
