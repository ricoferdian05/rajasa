<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Rajasa Finishing',
        ];

        return view('customer/index', $data);
    }

    public function produk()
    {
        $kategori = ['Kalender', 'Buku', 'Undangan', 'Brosur', 'Apron', 'Kaos', 'Totebag', 'Pouchbag'];
        $data = [
            'title' => 'Rajasa Finishing | Kalender',
            'kategori' => $kategori,
        ];

        return view('customer/produk', $data);
    }
}
