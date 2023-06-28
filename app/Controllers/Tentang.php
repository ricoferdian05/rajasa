<?php

namespace App\Controllers;

class Tentang extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Tentang Kami',
            'keyword'  => null,
        ];

        return view('customer/tentang', $data);
    }
}
