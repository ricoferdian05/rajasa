<?php

namespace App\Controllers;

class Tentang extends BaseController
{
    private $builderAkun;

    public function __construct()
    {
        $this->builderAkun = new \App\Models\CustomerModel();
    }

    public function index()
    {
        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Tentang Kami',
            'keyword'  => null,
            'akun' => $akun,
        ];

        return view('customer/tentang', $data);
    }
}
