<?php

namespace App\Controllers;

class Customer extends BaseController
{
    private $builderProduk;
    private $builderKategori;
    private $uri;

    public function __construct()
    {
        $this->builderProduk = new \App\Models\ProdukModel();
        $this->builderKategori = new \App\Models\KategoriModel();
        $this->uri = service('uri');
    }

    public function index()
    {
        $data = [
            'title' => 'Rajasa Finishing',
            'keyword'  => null,
        ];

        return view('customer/index', $data);
    }

    public function produk($kat, $filter)
    {
        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->get()->getResult();

        // PRODUK
        $queryProduk = $this->builderProduk;
        // PRODUK DENGAN KATEGORI
        if ($kat !== 'all') {
            for ($i = 0; $i < count($kategori); $i++) {
                if ($kategori[$i]->kategori === $kat) {
                    $idKategori = $kategori[$i]->id;
                }
            }
            $queryProduk = $queryProduk->where('idKategori', $idKategori);
        }

        // PRODUK DENGAN SEARCH
        $keywords = $this->request->getVar('keyword');
        if ($keywords !== null) {
            $queryProduk = $queryProduk->like('judul', $keywords);
        }

        // PRODUK DENGAN FILTER
        if ($filter === 'terbaru') {
            $queryProduk = $queryProduk->orderBy('created', 'DESC');
        } else if ($filter === 'terendah') {
            $queryProduk = $queryProduk->orderBy('harga', 'ASC');
        } else if ($filter === 'tertinggi') {
            $queryProduk = $queryProduk->orderBy('harga', 'DESC');
        }

        // PAGINATION
        $produk = $queryProduk->paginate(30, 'produk');
        $pager = $queryProduk->pager;

        // MENGHITUNG SEMUA PRODUK
        $countProduk = count($produk);
        // dd($produk);

        $data = [
            'title' => 'Rajasa Finishing | Kalender',
            'segment1' => $this->uri->getSegment(1),
            'segment2' => $this->uri->getSegment(2),
            'segment3' => $this->uri->getSegment(3),
            'keyword'  => $keywords,
            'kategori' => $kategori,
            'produk' => $produk,
            'jumlahProduk' => $countProduk,
            'pager' => $pager,
        ];

        return view('customer/produk', $data);
    }

    public function detailProduk($id)
    {
        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->get()->getResult();

        // DETAIL PRODUK
        $queryProduk = $this->builderProduk;
        $produk = $queryProduk->find($id);

        $data = [
            'title' => 'Rajasa Finishing | Kalender',
            'keyword'  => null,
            'kategori' => $kategori,
            'produk' => $produk,
        ];

        return view('customer/detail-produk', $data);
    }
}
