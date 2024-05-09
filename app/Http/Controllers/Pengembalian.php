<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Pengembalian extends BaseController
{
    public function index()
    {
        $module = 'Data Pengembalian';
        return view('admin.pengembalian.index', compact('module'));
    }
}
