<?php

namespace App\Controllers;

class Designer extends BaseController
{
    private $builderAkun;
    private $uri;
    private $builderKategori;
    private $builderTransaksi;
    private $builderDesigner;
    private $builderProduk;

    public function __construct()
    {
        $this->builderAkun = new \App\Models\DesignerModel();
        $this->uri = service('uri');
        $this->builderKategori = new \App\Models\KategoriModel();
        $this->builderTransaksi = new \App\Models\TransaksiModel();
        $this->builderDesigner = new \App\Models\DesignerModel();
        $this->builderProduk = new \App\Models\ProdukModel();
    }

    public function index($tahunKategori = 'all', $tahunProfit = 'all', $tahunPerformansi = null)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        $id = session()->get('id');

        // DESIGNER
        $queryDesigner = $this->builderDesigner;
        $designer = $queryDesigner->findAll();

        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->findAll();

        // TRANSAKSI KATEGORI
        $queryTransaksiKategori = $this->builderTransaksi;
        $queryTransaksiKategori->select('*');
        $queryTransaksiKategori->selectSum('jumlah');
        $queryTransaksiKategori->groupBy('idKategori');
        if ($tahunKategori !== null && $tahunKategori !== 'all') {
            $queryTransaksiKategori->where('EXTRACT(year FROM tanggal_pengiriman)', $tahunKategori);
        }
        $queryKategori->where('idDesigner', session()->get('id'));
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
        $queryProfitPertahun->where('idDesigner', session()->get('id'));
        $profitPertahun = $queryProfitPertahun->findAll();

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
            "transaksi.idDesigner = '{$id}' AND
            transaksi.status = 'On Going' OR 
            transaksi.status_transfer = 'Belum' AND
            transaksi.status_transfer = 'Selesai'"
        );
        $queryTransaksiTerbaru->orderBy('transaksi.tanggal_transaksi', 'ASC');


        $jumlahTransaksiTerbaru = $queryTransaksiTerbaru->countAllResults(false);

        $transaksiTerbaru = $queryTransaksiTerbaru->findAll();

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
            'transaksiTerbaru' => $transaksiTerbaru,
            'jumlahTransaksiTerbaru' => $jumlahTransaksiTerbaru,
            'keywordTransaksiTerbaru' => $keywordTransaksiTerbaru,
        ];

        return view('designer/index', $data);
    }

    public function chat()
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Chat | Rajasa Finishing',
            'akun' => $akun,
            'segment2' => $this->uri->getSegment(2),
        ];

        return view('designer/chat', $data);
    }

    public function produk($jenisKategori)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->findAll();

        // SEARCH
        $keyword = $this->request->getVar('keyword');

        $queryProduk = $this->builderProduk;
        $queryProduk->select('
        produk.id AS id,
        produk.judul AS judul,
        produk.harga AS harga,
        produk.gambar1 AS gambar1,
        produk.status AS status,
        produk.rating AS rating,
        produk.terjual AS terjual,
        produk.created AS created,
        kategori.kategori AS kategori,
        designer.nama AS designer,
        ');
        $queryProduk->join('kategori', 'kategori.id = produk.idKategori');
        $queryProduk->join('designer', 'designer.id = produk.idDesigner');
        $queryProduk->where('produk.idDesigner', session()->get('id'));
        if ($jenisKategori !== 'all') {
            $queryProduk->where('kategori.kategori', $jenisKategori);
        }
        if ($keyword !== null) {
            $queryProduk->like('produk.judul', $keyword);
        }

        // PAGINATION
        $produk = $queryProduk->paginate(10, 'produkDesigner');
        $pager = $queryProduk->pager;


        $urutan = $this->request->getVar('page_produkDesigner') ? $this->request->getVar('page_produkDesigner') : 1;

        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Data Produk | Rajasa Finishing',
            'akun' => $akun,
            'segment2' => $this->uri->getSegment(2),
            'segment3' => $this->uri->getSegment(3),
            'kategori' => $kategori,
            'produk' => $produk,
            'pager' => $pager,
            'urutan' => $urutan,
            'keyword' => $keyword,
        ];

        return view('designer/data-produk', $data);
    }

    public function tambahProduk()
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        if ($this->request->getVar('tambah')) {
            $judul = $this->request->getVar('judul');
            $kategori = $this->request->getVar('kategori');
            $harga = $this->request->getVar('harga');
            $deskripsi = $this->request->getVar('deskripsi');
            $gambar1 = $this->request->getFile('gambar1');
            $gambar2 = $this->request->getFile('gambar2');
            $gambar3 = $this->request->getFile('gambar3');
            $status = $this->request->getVar('status');

            // MENCARI KATEGORI PRODUK
            $queryKategoriProduk = $this->builderKategori;
            $queryKategoriProduk = $queryKategoriProduk->find($kategori);
            $kategoriProduk = $queryKategoriProduk['kategori'];

            $id = uniqid('prod-' . $kategoriProduk . '-', true);

            // GAMBAR PRODUK
            $pathUpload = 'asset/produk/' . $kategoriProduk;

            // GAMBAR 1
            if ($gambar1->getError() == 4) {
                $gambar1Name = "asset/website/image-default.png";
            } else {
                // RENAME FILE
                $gambar1Name = $gambar1->getRandomName();
                // MOVE FILE
                $gambar1->move($pathUpload, $gambar1Name);
                $gambar1Name = $pathUpload . '/' . $gambar1Name;
            }
            // GAMBAR 2
            if ($gambar2->getError() == 4) {
                $gambar2Name = "";
            } else {
                // RENAME FILE
                $gambar2Name = $gambar2->getRandomName();
                // MOVE FILE
                $gambar2->move($pathUpload, $gambar2Name);
                $gambar2Name = $pathUpload . '/' . $gambar2Name;
            }
            // GAMBAR 3
            if ($gambar3->getError() == 4) {
                $gambar3Name = "";
            } else {
                // RENAME FILE
                $gambar3Name = $gambar3->getRandomName();
                // MOVE FILE
                $gambar3->move($pathUpload, $gambar3Name);
                $gambar3Name = $pathUpload . '/' . $gambar3Name;
            }

            date_default_timezone_set('Asia/Jakarta');

            $dataProduk = [
                'id' => $id,
                'judul' => $judul,
                'harga' => $harga,
                'deskripsi' => $deskripsi,
                'gambar1' => $gambar1Name,
                'gambar2' => $gambar2Name,
                'gambar3' => $gambar3Name,
                'status' => $status,
                'rating' => 0,
                'terjual' => 0,
                'created' => date("Y-m-d H:i:s"),
                'idKategori' => $kategori,
                'idDesigner' => session()->get('id'),
            ];

            $queryProduk = $this->builderProduk;
            if ($queryProduk->insert($dataProduk) === 0) {
                session()->setFlashdata('add_success', 'Produk Berhasil Ditambahkan!!!');
            } else {
                session()->setFlashdata('add_error', 'Produk Gagal Ditambahkan!!!');
            }
        }

        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->findAll();

        // DESIGNER
        $queryDesigner = $this->builderDesigner;
        $designer = $queryDesigner->findAll();

        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Tambah Produk | Rajasa Finishing',
            'akun' => $akun,
            'segment2' => $this->uri->getSegment(2),
            'kategori' => $kategori,
            'designer' => $designer,
        ];

        return view('designer/data-produk/tambah', $data);
    }

    public function akun()
    {
        //CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        $queryAkun = $this->builderAkun;
        $akun = $queryAkun->find(session()->get('id'));

        $data = [
            'title' => 'Pengaturan Akun | Rajasa Finishing',
            'segment2' => $this->uri->getSegment(2),
            'akun' => $akun,
        ];

        return view('designer/akun', $data);
    }

    public function saveAkun($id)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        $queryAkun = $this->builderAkun;
        $akunLama = $queryAkun->find($id);

        $avatar = $this->request->getFile('avatar');
        $nama = $this->request->getVar('nama');
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $alamat = $this->request->getVar('alamat');
        $noHp = $this->request->getVar('noHp');

        // CEK EMAIL
        $queryAkun = $this->builderAkun;
        $akun = $queryAkun->findAll();
        foreach ($akun as $akun) {
            if ($akun['email'] === $email && $akunLama['email'] !== $email) {
                session()->setFlashdata('update_error', 'Email Sudah Terdaftar!!!');
                return redirect()->back();
            }
        }

        // AVATAR
        $pathAvatar = 'asset/designer/akun/';

        if ($avatar->getError() == 4) {
            $avatarName = $akunLama['avatar'];
        } else {
            // RENAME FILE
            $avatarName = $avatar->getRandomName();
            // MOVE FILE
            $avatar->move($pathAvatar, $avatarName);
            $avatarName = $pathAvatar . $avatarName;

            // DELETE FILE LAMA
            if (
                $akunLama['avatar'] !== 'asset/designer/akun/avatar-designer.png'
                && file_exists($akunLama['avatar'])
            ) {
                unlink($akunLama['avatar']);
            }
        }

        // Check space
        $arrPassword = explode(' ', $password);
        $arrUsername = explode(' ', $username);
        $arrName     = explode(' ', $nama);

        if ($arrPassword[0] === '' || $arrUsername[0] === '' || $arrName[0] === '') {
            session()->setFlashdata('update_error', 'Isi data dengan benar!');
            return redirect()->back();
        } else {
            // Cek Password
            if ($akunLama['password'] === $password) {
                $generatePassword = $akunLama['password'];
            } else {
                // Generate Password
                $generatePassword = password_hash($password, PASSWORD_DEFAULT);
            }

            $dataUpdate = [
                'id' => $id,
                'email' => $email,
                'password' => $generatePassword,
                'username' => $username,
                'nama' => $nama,
                'alamat' => $alamat,
                'no_hp' => $noHp,
                'avatar' => $avatarName,
                'tipe' => 2
            ];

            $akunUpdate = $this->builderAkun;
            if ($akunUpdate->save($dataUpdate)) {
                session()->setFlashdata('update_success', 'Akun Berhasil Diperbarui!!!');
            } else {
                session()->setFlashdata('update_error', 'Akun Gagal Diperbarui!!!');
            }

            // CEK EMAIL DIUBAH
            if ($akunLama['email'] !== $email) {
                $session = session();
                $session->destroy();
                return redirect()->to(base_url('login'));
            }

            return redirect()->back();
        }
    }

    public function detailsProduk($id)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        if ($this->request->getVar('update')) {
            // PRODUK
            $queryProduk = $this->builderProduk;
            $produk = $queryProduk->find($id);

            $judul = $this->request->getVar('judul');
            $kategori = $this->request->getVar('kategori');
            $harga = $this->request->getVar('harga');
            $deskripsi = $this->request->getVar('deskripsi');
            $gambar1 = $this->request->getFile('gambar1');
            $gambar2 = $this->request->getFile('gambar2');
            $gambar3 = $this->request->getFile('gambar3');
            $status = $this->request->getVar('status');
            $designerProduk = $this->request->getVar('designer');

            // MENCARI KATEGORI PRODUK
            $queryKategoriProduk = $this->builderKategori;
            $queryKategoriProduk = $queryKategoriProduk->find($kategori);
            $kategoriProduk = $queryKategoriProduk['kategori'];

            // GAMBAR PRODUK
            $pathUpload = 'asset/produk/' . $kategoriProduk;

            // GAMBAR 1
            if ($gambar1->getError() == 4) {
                $gambar1Name = $produk['gambar1'];
            } else {
                // RENAME FILE
                $gambar1Name = $gambar1->getRandomName();
                // MOVE FILE
                $gambar1->move($pathUpload, $gambar1Name);
                $gambar1Name = $pathUpload . '/' . $gambar1Name;

                // DELETE FILE LAMA
                if (
                    $produk['gambar1'] !== 'asset/website/image-default.png'
                    && file_exists($produk['gambar1'])
                ) {
                    unlink($produk['gambar1']);
                }
            }
            // GAMBAR 2
            if ($gambar2->getError() == 4) {
                $gambar2Name = $produk['gambar2'];
            } else {
                // RENAME FILE
                $gambar2Name = $gambar2->getRandomName();
                // MOVE FILE
                $gambar2->move($pathUpload, $gambar2Name);
                $gambar2Name = $pathUpload . '/' . $gambar2Name;

                // DELETE FILE LAMA
                if (
                    $produk['gambar2'] !== 'asset/website/image-default.png'
                    && file_exists($produk['gambar2'])
                ) {
                    unlink($produk['gambar2']);
                }
            }
            // GAMBAR 3
            if ($gambar3->getError() == 4) {
                $gambar3Name = $produk['gambar3'];
            } else {
                // RENAME FILE
                $gambar3Name = $gambar3->getRandomName();
                // MOVE FILE
                $gambar3->move($pathUpload, $gambar3Name);
                $gambar3Name = $pathUpload . '/' . $gambar3Name;

                // DELETE FILE LAMA
                if (
                    $produk['gambar3'] !== 'asset/website/image-default.png'
                    && file_exists($produk['gambar3'])
                ) {
                    unlink($produk['gambar3']);
                }
            }

            $queryUpdateProduk = $this->builderProduk;

            $dataUpdate = [
                'id' => $id,
                'judul' => $judul,
                'harga' => $harga,
                'deskripsi' => $deskripsi,
                'gambar1' => $gambar1Name,
                'gambar2' => $gambar2Name,
                'gambar3' => $gambar3Name,
                'status' => $status,
                'rating' => $produk['rating'],
                'terjual' => $produk['terjual'],
                'idKategori' => $kategori,
                'idDesigner' => $designerProduk,
            ];

            if ($queryUpdateProduk->save($dataUpdate)) {
                session()->setFlashdata('update_success', 'Produk Berhasil Diubah!!!');
            } else {
                session()->setFlashdata('update_error', 'Produk Gagal Diubah!!!');
            }
        }

        // KATEGORI
        $queryKategori = $this->builderKategori;
        $kategori = $queryKategori->findAll();

        // PRODUK
        $queryProduk = $this->builderProduk;
        $produk = $queryProduk->find($id);

        // DESIGNER
        $queryDesigner = $this->builderDesigner;
        $designer = $queryDesigner->findAll();

        $akun = $this->builderAkun->find(session()->get('id'));
        $data = [
            'title' => 'Detail Produk | Rajasa Finising',
            'akun' => $akun,
            'segment2' => $this->uri->getSegment(2),
            'kategori' => $kategori,
            'designer' => $designer,
            'produk' => $produk,
        ];

        return view('designer/data-produk/detail', $data);
    }

    public function hapusProduk($id)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        // CARI DATA PRODUK
        $queryProduk = $this->builderProduk;
        $produk = $queryProduk->find($id);

        // MENGHAPUS GAMBAR
        if ($produk['gambar1'] !== null && $produk['gambar1'] !== 'asset/website/image-default.png' && file_exists($produk['gambar1'])) {
            unlink($produk['gambar1']);
        }
        if ($produk['gambar2'] !== null && file_exists($produk['gambar2'])) {
            unlink($produk['gambar2']);
        }
        if ($produk['gambar3'] !== null && file_exists($produk['gambar3'])) {
            unlink($produk['gambar3']);
        }

        // DELETE DATA PRODUK
        if ($queryProduk->delete(['id' => $id])) {
            session()->setFlashdata('delete_success', 'Produk Berhasil Dihapus!!!');
        } else {
            session()->setFlashdata('delete_error', 'Produk Gagal Dihapus!!!');
        }
        return redirect()->back();
    }

    public function transaksi($kategori = 'all', $pilihanTahun = null)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
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
        $queryTransaksi->where('transaksi.idDesigner', session()->get('id'));

        $jumlahTransaksi = $queryTransaksi->countAllResults(false);

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
            'segment4' => $this->uri->getSegment(4),
            'tahunTransaksi' => $tahunTransaksi,
            'pilihanTahun' => $pilihanTahun,
            'kategori' => $kategori,
            'keyword' => $keyword,
            'transaksi' => $transaksi,
            'jumlahTransaksi' => $jumlahTransaksi,
            'pager' => $pager,
            'urutan' => $urutan,
        ];

        return view('designer/transaksi', $data);
    }

    public function details($id)
    {
        // CHECK TIPE AKUN
        if (session()->get('tipe') !== '2') {
            return redirect()->to(base_url('/logout'));
        }

        // UPDATE DATA TRANSAKSI
        if ($this->request->getVar('update')) {
            $queryUpdateData = $this->builderTransaksi;
            $data_update = [
                'id' => $id,
                'tanggal_pengiriman' => $this->request->getVar('tanggal_pengiriman'),
                'status' => $this->request->getVar('status'),
                'status_transfer' => $this->request->getVar('status_transfer'),
            ];
            if ($queryUpdateData->save($data_update)) {
                session()->setFlashdata('update_success', 'Transaksi Berhasil Diupdate');
            } else {
                session()->setFlashdata('update_error', 'Transaksi Gagal Diupdate');
            }
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
            'segment3' => $this->uri->getSegment(3),
            'segment4' => $this->uri->getSegment(4),
        ];

        return view('designer/detail-transaksi', $data);
    }
}
