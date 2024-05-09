<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends BaseController
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->back();
        }
        return redirect()->route('login.login-akun');
    }

    public function dashboard()
    {
        $module = 'Dashboard';
        $anggota_tervierifikasi = User::where('role', 'mahasiswa')->where('status', 'Aktiv')->count();
        $anggota_nonvierifikasi = User::where('role', 'mahasiswa')->where('status', null)->count();
        $pinjam = Peminjaman::where('status', 'Terpinjam')->count();
        $kembali = Peminjaman::where('status', 'Kembali')->count();
        $buku = Buku::count();
        return view('dashboard.index', compact(
            'module',
            'anggota_tervierifikasi',
            'anggota_nonvierifikasi',
            'pinjam',
            'kembali',
            'buku'
        ));
    }

    public function areaChart()
    {
        // Mendapatkan data dari database menggunakan query builder Laravel
        $data = Peminjaman::selectRaw('COUNT(id) as count, TO_CHAR(created_at, \'Month\') as month_name')
            ->whereYear('created_at', '=', date('Y'))
            ->groupByRaw('TO_CHAR(created_at, \'Month\')')
            ->get();

        // Inisialisasi array data
        $result = [
            'label' => [],
            'data' => []
        ];

        // Memproses hasil query
        foreach ($data as $row) {
            $result['label'][] = $row->month_name;
            $result['data'][] = (int) $row->count;
        }

        // Mengembalikan data dalam format JSON
        return $this->sendResponse('Get data success', $result);
    }
}
