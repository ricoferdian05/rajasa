<?php

namespace App\Controllers;

class Customer extends BaseController
{
    private $builderProduk;
    private $builderKategori;

    public function __construct()
    {
        $this->builderProduk = new \App\Models\ProdukModel();
        $this->builderKategori = new \App\Models\KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Rajasa Finishing',
        ];

        return view('customer/index', $data);
    }

    public function produk()
    {
        // PRODUK DEFAULT (URUTAN TERBARU)
        $queryProduk = $this->builderProduk;
        $queryProduk = $queryProduk->orderBy('created', 'DESC');
        $produk = $queryProduk->get()->getResult();
        $countProduk = $this->builderProduk
            ->countAllResults();
        // dd($produk);

        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->get()->getResult();

        $data = [
            'title' => 'Rajasa Finishing | Kalender',
            'kategori' => $kategori,
            'produk' => $produk,
            'jumlahProduk' => $countProduk,
        ];

        return view('customer/produk', $data);
    }
}
