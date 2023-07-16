<?php

namespace App\Controllers;

class Admin extends BaseController
{
    private $builderAkun;
    private $uri;
    private $builderKategori;
    private $builderTransaksi;
    private $builderDesigner;


    public function __construct()
    {
        $this->builderAkun = new \App\Models\AdminModel();
        $this->uri = service('uri');
        $this->builderKategori = new \App\Models\KategoriModel();
        $this->builderTransaksi = new \App\Models\TransaksiModel();
        $this->builderDesigner = new \App\Models\DesignerModel();
    }

    public function index($tahunKategori = 'all', $tahunProfit = 'all', $tahunPerformansi = null)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '1') {
            return redirect()->to(base_url('/logout'));
        }

        // DESIGNER
        $queryDesigner = $this->builderDesigner;
        $designer = $queryDesigner->findAll();

        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->findAll();

        // LIST TAHUN TRANSAKSI
        $queryTahunTransaksi = $this->builderTransaksi;
        $queryTahunTransaksi->select('EXTRACT(year FROM tanggal_pengiriman) AS tahun');
        $queryTahunTransaksi->groupBy('EXTRACT(year FROM tanggal_pengiriman)');
        $tahunTransaksi = $queryTahunTransaksi->findAll();

        // TRANSAKSI KATEGORI
        $queryTransaksiKategori = $this->builderTransaksi;
        $queryTransaksiKategori->select('*');
        $queryTransaksiKategori->selectSum('jumlah');
        $queryTransaksiKategori->groupBy('idKategori');
        if ($tahunKategori !== null && $tahunKategori !== 'all') {
            $queryTransaksiKategori->where('EXTRACT(year FROM tanggal_pengiriman)', $tahunKategori);
        }
        $transaksiKategori = $queryTransaksiKategori->findAll();

        // TAHUN TRANSAKSI
        $queryTahunTransaksi = $this->builderTransaksi;
        $queryTahunTransaksi->select('EXTRACT(year FROM tanggal_pengiriman) AS tahun');
        $queryTahunTransaksi->groupBy('EXTRACT(year FROM tanggal_pengiriman)');
        $tahunTransaksi = $queryTahunTransaksi->findAll();

        // PROFIT PER TAHUN
        $queryProfitPertahun = $this->builderTransaksi;
        $queryProfitPertahun->select(
            'tanggal_pengiriman,
            EXTRACT(month FROM tanggal_pengiriman) AS bulan,
            EXTRACT(year FROM tanggal_pengiriman) AS tahun'
        );
        $queryProfitPertahun->selectSum('total_harga', 'total');
        if ($tahunProfit !== null && $tahunProfit !== 'all') {
            $queryProfitPertahun->groupBy('EXTRACT(month FROM tanggal_pengiriman)');
            $queryProfitPertahun->where('EXTRACT(year FROM tanggal_pengiriman)', $tahunProfit);
        } else {
            $queryProfitPertahun->groupBy('EXTRACT(year FROM tanggal_pengiriman)');
        }
        $profitPertahun = $queryProfitPertahun->findAll();

        // PERFORMANSI DESIGNER
        $keywordPerformansi = $this->request->getVar('keywordPerformansi');

        $queryPerformansiDesigner = $this->builderTransaksi;
        $queryPerformansiDesigner->select(
            'designer.id AS idDesigner, 
            designer.nama AS nama, 
            kategori.id AS idKategori, 
            kategori.kategori AS kategori,
            transaksi.jumlah AS jumlah,
            transaksi.tanggal_pengiriman'
        );
        $queryPerformansiDesigner->join('designer', 'designer.id = transaksi.idDesigner');
        $queryPerformansiDesigner->join('kategori', 'kategori.id = transaksi.idKategori');
        if ($tahunPerformansi !== null && $tahunPerformansi !== 'all') {
            $queryTransaksiKategori->where('EXTRACT(year FROM transaksi.tanggal_pengiriman)', $tahunPerformansi);
        }
        if ($keywordPerformansi !== null) {
            $queryPerformansiDesigner->like('designer.nama', $keywordPerformansi);
        }
        $performansiDesigner = $queryPerformansiDesigner->findAll();

        // KELOMPOK DESIGNER (PERFORMANSI DESIGNER)
        $queryKelompokDesigner = $this->builderTransaksi;
        $queryKelompokDesigner->select(
            'transaksi.idDesigner AS id,
            designer.nama AS nama'
        );
        $queryKelompokDesigner->join('designer', 'designer.id = transaksi.idDesigner');
        if ($keywordPerformansi !== null) {
            $queryKelompokDesigner->like('designer.nama', $keywordPerformansi);
        }
        $queryKelompokDesigner->groupBy('transaksi.idDesigner');
        $kelompokDesigner = $queryKelompokDesigner->findAll();

        // TRANSAKSI TERBARU
        $keywordTransaksiTerbaru = $this->request->getVar('keywordTransaksiTerbaru');

        $queryTransaksiTerbaru = $this->builderTransaksi;
        $queryTransaksiTerbaru->select('
            transaksi.id AS id,
            transaksi.tanggal_transaksi AS tanggal_transaksi,
            customer.nama AS nama_customer,
            produk.judul AS produk,
            kategori.kategori AS kategori,
            transaksi.jumlah AS jumlah,
            transaksi.total_harga AS total,
            transaksi.status AS status,
            transaksi.status_transfer AS status_transfer,
            designer.nama AS nama_designer
        ');
        $queryTransaksiTerbaru->join('customer', 'customer.id = transaksi.idCustomer');
        $queryTransaksiTerbaru->join('produk', 'produk.id = transaksi.idProduk');
        $queryTransaksiTerbaru->join('kategori', 'kategori.id = transaksi.idKategori');
        $queryTransaksiTerbaru->join('designer', 'designer.id = transaksi.idDesigner');
        if ($keywordTransaksiTerbaru !== null) {
            $queryTransaksiTerbaru->like('customer.nama', $keywordTransaksiTerbaru);
        }
        $queryTransaksiTerbaru->where(
            "transaksi.status = 'On Going' OR 
            transaksi.status_transfer = 'Belum' AND
            transaksi.status_transfer = 'Selesai'"
        );
        $queryTransaksiTerbaru->orderBy('transaksi.tanggal_transaksi', 'ASC');

        $transaksiTerbaru = $queryKelompokDesigner->findAll();

        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Dashboard | Rajasa Finishing',
            'keyword'  => null,
            'akun' => $akun,
            'segment2' => $this->uri->getSegment(2),
            'kategori' => $kategori,
            'designer' => $designer,
            'transaksiKategori' => $transaksiKategori,
            'profitPertahun' => $profitPertahun,
            'tahunTransaksi' => $tahunTransaksi,
            'tahunKategori' => $tahunKategori,
            'tahunProfit' => $tahunProfit,
            'tahunPerformansi' => $tahunPerformansi,
            'tahunTransaksi' => $tahunTransaksi,
            'performansiDesigner' => $performansiDesigner,
            'kelompokDesigner' => $kelompokDesigner,
            'transaksiTerbaru' => $transaksiTerbaru,
            'keywordPerformansi' => $keywordPerformansi,
            'keywordTransaksiTerbaru' => $keywordTransaksiTerbaru,
        ];

        return view('admin/index', $data);
    }

    public function transaksi($kategori = 'all', $pilihanTahun = null)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '1') {
            return redirect()->to(base_url('/logout'));
        }

        // KEYWORD
        $keyword = $this->request->getVar('keyword');

        // TAHUN TRANSAKSI
        $queryTahunTransaksi = $this->builderTransaksi;
        $queryTahunTransaksi->select('EXTRACT(year FROM tanggal_pengiriman) AS tahun');
        $queryTahunTransaksi->groupBy('EXTRACT(year FROM tanggal_pengiriman)');
        $tahunTransaksi = $queryTahunTransaksi->findAll();

        // TRANSAKSI
        $queryTransaksi = $this->builderTransaksi;
        $queryTransaksi->select('
            transaksi.id AS id,
            transaksi.tanggal_transaksi AS tanggal_transaksi,
            transaksi.tanggal_pengiriman AS tanggal_pengiriman,
            customer.nama AS nama_customer,
            produk.judul AS produk,
            kategori.kategori AS kategori,
            transaksi.jumlah AS jumlah,
            transaksi.total_harga AS total,
            transaksi.status AS status,
            transaksi.status_transfer AS status_transfer,
            designer.nama AS nama_designer
        ');
        $queryTransaksi->join('customer', 'customer.id = transaksi.idCustomer');
        $queryTransaksi->join('produk', 'produk.id = transaksi.idProduk');
        $queryTransaksi->join('kategori', 'kategori.id = transaksi.idKategori');
        $queryTransaksi->join('designer', 'designer.id = transaksi.idDesigner');
        if ($keyword !== null) {
            $queryTransaksi->like('customer.nama', $keyword);
        }
        if ($kategori === 'selesai') {
            $queryTransaksi->where('transaksi.status', 'selesai');
        } elseif ($kategori === 'sedang-dikerjakan') {
            $queryTransaksi->where('transaksi.status', 'On Going');
            $queryTransaksi->where('transaksi.status_transfer', 'Selesai');
        } elseif ($kategori === 'belum-dibayar') {
            $queryTransaksi->where('transaksi.status_transfer', 'Belum');
        }
        if ($pilihanTahun !== null) {
            $queryTransaksi->where('EXTRACT(year FROM transaksi.tanggal_transaksi)', $pilihanTahun);
        }
        $queryTransaksi->orderBy('transaksi.status', 'ASC');

        // PAGINATION
        $transaksi = $queryTransaksi->paginate(10, 'transaksi');
        $pager = $queryTransaksi->pager;

        $urutan = $this->request->getVar('page_transaksi') ? $this->request->getVar('page_transaksi') : 1;


        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Transaksi | Rajasa Finishing',
            'akun' => $akun,
            'segment2' => $this->uri->getSegment(2),
            'segment3' => $this->uri->getSegment(3),
            'tahunTransaksi' => $tahunTransaksi,
            'pilihanTahun' => $pilihanTahun,
            'kategori' => $kategori,
            'keyword' => $keyword,
            'transaksi' => $transaksi,
            'pager' => $pager,
            'urutan' => $urutan,
        ];

        return view('admin/transaksi', $data);
    }

    public function details($id)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '1') {
            return redirect()->to(base_url('/logout'));
        }

        // TRANSAKSI
        $queryTransaksi = $this->builderTransaksi;
        $queryTransaksi->select('
             transaksi.id AS id,
             transaksi.tanggal_transaksi AS tanggal_transaksi,
             transaksi.tanggal_pengiriman AS tanggal_pengiriman,
             customer.nama AS nama_customer,
             customer.no_hp AS no_hp_customer,
             customer.alamat AS alamat_customer,
             customer.kode_pos AS kode_pos_customer,
             produk.id AS id_produk,
             produk.judul AS produk,
             kategori.kategori AS kategori,
             transaksi.jumlah AS jumlah,
             transaksi.total_harga AS total,
             transaksi.status AS status,
             transaksi.status_transfer AS status_transfer,
             designer.nama AS nama_designer
         ');
        $queryTransaksi->join('customer', 'customer.id = transaksi.idCustomer');
        $queryTransaksi->join('produk', 'produk.id = transaksi.idProduk');
        $queryTransaksi->join('kategori', 'kategori.id = transaksi.idKategori');
        $queryTransaksi->join('designer', 'designer.id = transaksi.idDesigner');
        $queryTransaksi->where('transaksi.id', $id);
        $transaksi = $queryTransaksi->get()->getResult();

        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Detail Transaksi | Rajasa Finishing',
            'akun' => $akun,
            'transaksi' => $transaksi,
            'segment2' => $this->uri->getSegment(2),

        ];

        return view('admin/detail-transaksi', $data);
    }
}
