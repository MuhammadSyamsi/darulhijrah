<?php

namespace App\Controllers;

use App\Models\AlumniModel;
use App\Models\DetailModel;
use App\Models\PsbModel;
use App\Models\TransferModel;
use App\Models\SantriModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Page extends BaseController
{

  public function nextmonth() {
    $santri = new SantriModel();
    $lopsan = $santri->where('kelas !=', 'keluar')->findAll();
    foreach ($lopsan as $loop) {
      $baru = $loop['tunggakanspp'] + $loop['spp'];
      $loop['tunggakanspp'] = $baru;
      $santri->update($loop['nisn'], $loop);
    }

    echo '<script>alert("Proses berhasil dilakukan!");</script>';

    return redirect()->to(base_url());
  }

  public function naikkelas() {
    $santri = new SantriModel();
    //naik kelas
    $lop1 = $santri->where('kelas', '9')->orWhere('kelas', '12')->findAll();
    foreach ($lop1 as $lop) {
      $lop['kelas'] = 'lulus';
      $santri->update($lop['nisn'], $lop);
    }
    $lop2 = $santri->where('kelas', '7')->orWhere('kelas', '8')->orWhere('kelas', '10')->orWhere('kelas', '11')->findAll();
    foreach ($lop2 as $loop) {
      $baru2b = $loop['kelas'] + 1;
      $loop['kelas'] = $baru2b;
      $santri->update($loop['nisn'], $loop);
    }

    echo '<script>alert("Proses berhasil dilakukan!");</script>';

    return redirect()->to(base_url());
  }

  public function tambah() {

    $cariNama = new SantriModel();
    $data['cari'] = $cariNama->findAll();
    $santri = $cariNama->findAll();

    return view('pages/insert', $data);
    return $this->response->setJSON($santri);
  }

  public function save() {
    $postModel = new TransferModel();
    $postDetail = new DetailModel();
    $postKewajiban = new SantriModel();
    $postModel->insert([
      'idtrans' => $this->request->getPost('id'),
      'nisn' => $this->request->getPost('nisn'),
      'nama' => $this->request->getPost('nama'),
      'program' => $this->request->getPost('program'),
      'kelas' => $this->request->getPost('kelas'),
      'saldomasuk' => $this->request->getPost('saldomasuk'),
      'tanggal' => $this->request->getPost('tanggal'),
      'keterangan' => $this->request->getPost('keterangan'),
      'rekening' => $this->request->getPost('rekening')
    ]);

    $postDetail->insert([
      'id' => $this->request->getPost('id'),
      'program' => $this->request->getPost('program'),
      'tanggal' => $this->request->getPost('tanggal'),
      'rekening' => $this->request->getPost('rekening'),
      'daftarulang' => $this->request->getPost('tunggakandu'),
      'tunggakan' => $this->request->getPost('tunggakantl'),
      'spp' => $this->request->getPost('tunggakanspp'),
      'uangsaku' => $this->request->getPost('uangsaku'),
      'infaq' => $this->request->getPost('infaq'),
      'formulir' => $this->request->getPost('formulir')
    ]);

    $hitungDu = 0;
    $hitungTl = 0;
    $hitungSpp = 0;
    $spp = $postKewajiban->where('nisn', $this->request->getPost('nisn'))->findAll();
    $input = (int) $this->request->getPost('tunggakandu'); // nilai input pengurangan

foreach ($spp as $ts) {

    $du1 = (int) $ts['tunggakandu'];
    $du2 = (int) $ts['tunggakandu2'];
    $du3 = (int) $ts['tunggakandu3'];

    // --- Kurangi tunggakandu (1) ---
    if ($input > 0) {
        $kurang = min($du1, $input);
        $du1 -= $kurang;
        $input -= $kurang;
    }

    // --- Kurangi tunggakandu2 (2) ---
    if ($input > 0) {
        $kurang = min($du2, $input);
        $du2 -= $kurang;
        $input -= $kurang;
    }

    // --- Kurangi tunggakandu3 (3) ---
    if ($input > 0) {
        $kurang = min($du3, $input);
        $du3 -= $kurang;
        $input -= $kurang;
    }
}

$hitungSpp = $ts['tunggakanspp'] - $this->request->getPost('tunggakanspp');
$hitungTl = $ts['tunggakantl'] - $this->request->getPost('tunggakantl');


// Simpan hasil
$postKewajiban->save([
    'nisn' => $this->request->getPost('nisn'),
    'tunggakandu' => $du1,
    'tunggakandu2' => $du2,
    'tunggakandu3' => $du3,
      'tunggakantl' => $hitungTl,
      'tunggakanspp' => $hitungSpp    
]);

    // $data = ['id' => $this->request->getPost('id')];
    $id = $this->request->getPost('id');
    return redirect()->to('/kwitansi/' . $id);  }

  public function kwitansi_santri_aktif($idtrans)
    {
        $transferModel = new TransferModel();
        $detailModel   = new DetailModel();
        $santriModel   = new SantriModel();

        // Data utama transaksi
        $transfer = $transferModel->find($idtrans);
        if (!$transfer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Transaksi dengan ID $idtrans tidak ditemukan");
        }

        // Data detail transaksi
        $detail = $detailModel->find($idtrans);

        // Data santri berdasarkan NISN dari transfer
        $santri = null;
        if ($transfer['nisn']) {
            $santri = $santriModel->where('nisn', $transfer['nisn'])->first();
        }

        $data = [
            'transfer' => $transfer,
            'detail'   => $detail,
            'santri'   => $santri,
        ];

        return view('pages/kwitansi-update', $data);
    }

  public function kwitansi_santri_psb($idtrans)
    {
        $transferModel = new TransferModel();
        $detailModel   = new DetailModel();
        $santriModel   = new PsbModel();

        // Data utama transaksi
        $transfer = $transferModel->find($idtrans);
        if (!$transfer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Transaksi dengan ID $idtrans tidak ditemukan");
        }

        // Data detail transaksi
        $detail = $detailModel->find($idtrans);

        // Data santri berdasarkan NISN dari transfer
        $santri = null;
        if ($transfer['nisn']) {
            $santri = $santriModel->where('nisn', $transfer['nisn'])->first();
        }

        $data = [
            'transfer' => $transfer,
            'detail'   => $detail,
            'santri'   => $santri,
        ];

        return view('pages/kwitansi-update', $data);
    }

  public function kwitansi_santri_alumni($idtrans)
    {
        $transferModel = new TransferModel();
        $detailModel   = new DetailModel();
        $santriModel   = new AlumniModel();

        // Data utama transaksi
        $transfer = $transferModel->find($idtrans);
        if (!$transfer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Transaksi dengan ID $idtrans tidak ditemukan");
        }

        // Data detail transaksi
        $detail = $detailModel->find($idtrans);

        // Data santri berdasarkan NISN dari transfer
        $santri = null;
        if ($transfer['nisn']) {
            $santri = $santriModel->where('nisn', $transfer['nisn'])->first();
        }

        $data = [
            'transfer' => $transfer,
            'detail'   => $detail,
            'santri'   => $santri,
        ];

        return view('pages/kwitansi-update', $data);
    }
    
    public function claim_laundry()
    {
        $transferModel = new TransferModel();
        $santriModel   = new AlumniModel();

        // Filter bulan & tahun Laundry
        $bulan  = $this->request->getGet('bulan') ?? date('n');
        $tahun  = $this->request->getGet('tahun') ?? date('Y');
    
        // Filter bulan & tahun Non-Laundry
        $bulan3 = $this->request->getGet('bulan3') ?? date('n');
        $tahun3 = $this->request->getGet('tahun3') ?? date('Y');
    
        // Filter bulan & tahun Santri
        $bulan2 = $this->request->getGet('bulan2') ?? date('n');
        $tahun2 = $this->request->getGet('tahun2') ?? date('Y');
    
        // --- Data Laundry ---
        $transferLaundry = $transferModel
            ->like('keterangan', 'laundry')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->findAll();
    
        // --- Data Non-Laundry ---
        $transferNonLaundry = $transferModel
            ->select('transfer.*, santri.spp')
            ->join('santri', 'santri.nisn = transfer.nisn')
            ->where('santri.spp !=', 1500000)
            ->notLike('transfer.keterangan', 'laundry')
            ->where('MONTH(transfer.tanggal)', $bulan3)
            ->where('YEAR(transfer.tanggal)', $tahun3)
            ->whereIn('transfer.kelas', [7, 8, 9])
            ->findAll();
    
        // --- Data Santri ---
        $transferSantri = $transferModel
            ->select('transfer.*, santri.spp')
            ->join('santri', 'santri.nisn = transfer.nisn')
            ->where('santri.spp', 1500000)
            ->where('santri.jenjang', 'MTs')
            ->like('transfer.keterangan', 'SPP')
            ->where('MONTH(transfer.tanggal)', $bulan2)
            ->where('YEAR(transfer.tanggal)', $tahun2)
            ->findAll();
    
        $data = [
            'transferLaundry'    => $transferLaundry,
            'transferNonLaundry' => $transferNonLaundry,
            'transferSantri'     => $transferSantri,
        ];
    
        return view('kantin/transfer_view', $data);
    }
    
  public function dtransaksi($idtrans) {
    $transfer = new TransferModel();
    $detailMod = new DetailModel();
    $santri = new SantriModel();
    $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
    $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
    $data['detail'] = $detailMod->where('id', $idtrans)->find();
    $data['santri'] = $santri->where('nama', $nama)->find();
    return view('pages/edit_transaksi', $data);
  }

  public function edit() {
    $postModel = new TransferModel();
    $postDetail = new DetailModel();
    $santri = new SantriModel();
    $postModel->save([
      'idtrans' => $this->request->getPost('idtrans'),
      'nisn' => $this->request->getPost('nisn'),
      'nama' => $this->request->getPost('nama'),
      'kelas' => $this->request->getPost('kelas'),
      'saldomasuk' => $this->request->getPost('saldomasuk'),
      'tanggal' => $this->request->getPost('tanggal'),
      'keterangan' => $this->request->getPost('keterangan'),
      'rekening' => $this->request->getPost('rekening')
    ]);
    $postDetail->save([
      'id' => $this->request->getPost('idtrans'),
      'tanggal' => $this->request->getPost('tanggal'),
      'daftarulang' => $this->request->getPost('du'),
      'tunggakan' => $this->request->getPost('tunggakan'),
      'spp' => $this->request->getPost('spp'),
      'uangsaku' => $this->request->getPost('uangsaku'),
      'infaq' => $this->request->getPost('infaq'),
      'formulir' => $this->request->getPost('formulir'),
      'rekening' => $this->request->getPost('rekening')
    ]);
    $santri->save([
      'nisn' => $this->request->getPost('nisn'),
      'nama' => $this->request->getPost('nama'),
      'tunggakandu' => $this->request->getPost('santridu'),
      'tunggakantl' => $this->request->getPost('santritl'),
      'tunggakanspp' => $this->request->getPost('santrispp')
    ]);

    $data = [
      'id' => $this->request->getPost('idtrans'),
    ];

    return redirect()->to('/riwayat-pembayaran');
  }

  public function delet($idtrans) {
    $transferModel = new TransferModel();
    $detailModel = new DetailModel();
    $transferModel->delete($idtrans);
    $detailModel->delete($idtrans);

    echo '<script>alert("Proses berhasil dilakukan!");</script>';

    return redirect()->to(base_url('/riwayat-pembayaran'));
  }

public function mutasi()
{
    $transferModel = new TransferModel();
    $transferPSB = new TransferModel();
    $transferAlumni = new TransferModel();

    $keyword = $this->request->getPost('keyword');
    $tanggalAwal = $this->request->getPost('tanggal_awal') ?? date('Y-m-d');
    $tanggalAkhir = $this->request->getPost('tanggal_akhir') ?? date('Y-m-d');

    if ($keyword) {
        $transfer = $transferModel->search($keyword);
        $transferpsbmodel = $transferPSB->search($keyword);
        $transferalumnimodel = $transferAlumni->search($keyword);
    } else {
        $transfer = $transferModel;
        $transferpsbmodel = $transferPSB;
        $transferalumnimodel = $transferAlumni;
    }

    // Ambil daftar unik rekening
    $rekeningList = $transferModel->select('rekening')
                                  ->distinct()
                                  ->orderBy('rekening', 'asc')
                                  ->findAll();

    $data = [
        'transferpsb' => $transferpsbmodel->orderBy('tanggal', 'desc')
                                          ->where('program', 'psb')
                                          ->where('tanggal >=', $tanggalAwal)
                                          ->where('tanggal <=', $tanggalAkhir)
                                          ->findAll(),
        'transfer' => $transfer->orderBy('tanggal', 'desc')
                               ->where('kelas !=', 'lulus')
                               ->where('program !=', 'psb')
                               ->where('tanggal >=', $tanggalAwal)
                               ->where('tanggal <=', $tanggalAkhir)
                               ->findAll(),
        'transferalumni' => $transferalumnimodel->orderBy('tanggal', 'desc')
                                               ->where('kelas', 'lulus')
                                               ->where('tanggal >=', $tanggalAwal)
                                               ->where('tanggal <=', $tanggalAkhir)
                                               ->findAll(),
        'rekeningList' => array_column($rekeningList, 'rekening'),
        'tanggalAwal' => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir,
    ];

    return view('pages/mutasi', $data);
}

public function cariMutasi()
{
    if (!$this->request->isAJAX()) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $json = $this->request->getJSON(true); // true => array, lebih aman

    $keyword       = $json['keyword'] ?? '';
    $tanggalAwal   = $json['tanggal_awal'] ?? '';
    $tanggalAkhir  = $json['tanggal_akhir'] ?? '';
    $rekening      = $json['rekening'] ?? '';
    $program       = $json['program'] ?? '';

    $model = new TransferModel();
    $tableName = $model->getTable();

    // 🔹 Helper closure untuk filter dinamis
    $applyFilters = function ($builder) use ($keyword, $tanggalAwal, $tanggalAkhir, $rekening, $program) {
        if ($keyword !== '') {
            $builder->groupStart()
                ->like('nama', $keyword)
                ->orLike('kelas', $keyword)
                ->orLike('keterangan', $keyword)
                ->groupEnd();
        }

        if ($tanggalAwal !== '' && $tanggalAkhir !== '') {
            $builder->where('tanggal >=', $tanggalAwal)
                    ->where('tanggal <=', $tanggalAkhir);
        } else {
            $builder->where('DATE(tanggal)', date('Y-m-d')); // default: hari ini
        }

        if ($rekening !== '') {
            $builder->where('rekening', $rekening);
        }

        if ($program !== '') {
            $builder->where('program', $program);
        }

        return $builder;
    };

    // 🔹 PSB
    $builderPsb = $model->db->table($tableName);
    $psb = $applyFilters($builderPsb->where('program', 'psb'))
        ->orderBy('tanggal', 'desc')
        ->get()->getResultArray();

    // 🔹 Santri Aktif
    $builderSantri = $model->db->table($tableName);
    $santri = $applyFilters(
        $builderSantri->where('kelas !=', 'lulus')->where('program !=', 'psb')
    )
        ->orderBy('tanggal', 'desc')
        ->get()->getResultArray();

    // 🔹 Alumni
    $builderAlumni = $model->db->table($tableName);
    $alumni = $applyFilters($builderAlumni->where('kelas', 'lulus'))
        ->orderBy('tanggal', 'desc')
        ->get()->getResultArray();

    return $this->response->setJSON([
        'psb'    => $psb,
        'santri' => $santri,
        'alumni' => $alumni,
    ]);
}

public function download_datapembayaran()
{
    $transferModel = new \App\Models\TransferModel();

    $tanggalAwal = $this->request->getGet('tanggal_awal') ?? date('Y-m-d');
    $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
    $rekening = $this->request->getGet('rekening') ?? '';
    $program = $this->request->getGet('program') ?? '';
    $jenis = $this->request->getGet('jenis') ?? '';

    if ($rekening) $transferModel->where('rekening', $rekening);
    if ($program) $transferModel->where('program', $program);
    $transferModel->where('tanggal >=', $tanggalAwal)->where('tanggal <=', $tanggalAkhir);

    if ($jenis === 'psb') $transferModel->where('program', 'psb');
    elseif ($jenis === 'alumni') $transferModel->where('kelas', 'lulus');
    else $transferModel->where('program !=', 'psb')->where('kelas !=', 'lulus');

    $data = $transferModel->orderBy('tanggal', 'desc')->findAll();

    // Output CSV
    $filename = 'mutasi_' . $jenis . '_' . date('Ymd_His') . '.csv';
    $output = fopen('php://memory', 'w');
    fputcsv($output, ['Tanggal', 'Nama', 'Kelas', 'Rekening', 'Program', 'Saldo Masuk', 'Keterangan']);
    foreach ($data as $row) {
        fputcsv($output, [
            $row['tanggal'], $row['nama'], $row['kelas'], $row['rekening'], 
            $row['program'], $row['saldomasuk'], $row['keterangan']
        ]);
    }
    fseek($output, 0);

    return $this->response
                ->setHeader('Content-Type', 'text/csv')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody(stream_get_contents($output));
}

  public function datatunggakan() {

    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $psb = new PsbModel();
    $keyword = $this->request->getPost('keyword');
    if ($keyword) {
      $datasantri = $santri->search($keyword);
      $dataalumni = $alumni->search($keyword);
      $datapsb = $psb->search($keyword);
    } else {
      $datasantri = $santri;
      $dataalumni = $alumni;
      $datapsb = $psb;
    }
    $data = [
      'title' => 'Tunggakan Santri Darul Hijrah',
      'transferpsb' => $datapsb->where('status', 'diterima')->findAll(5),
      'transfer' => $datasantri->findAll(5),
      'transferalumni' => $dataalumni->where('kelas', 'lulus')->findAll(5),
      'reminderSantri' => $datasantri
        ->where('tunggakanspp >', 0)
        ->orWhere('tunggakandu >', 0)
        ->findAll(),
    ];

    return view('pages/tunggakan', $data);
  }

  public function datatunggakanadmin() {

    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $psb = new PsbModel();
    $keyword = $this->request->getPost('keyword');
    if ($keyword) {
      $datasantri = $santri->search($keyword);
      $dataalumni = $alumni->search($keyword);
      $datapsb = $psb->search($keyword);
    } else {
      $datasantri = $santri;
      $dataalumni = $alumni;
      $datapsb = $psb;
    }
    $data = [
      'title' => 'Tunggakan Santri Darul Hijrah',
      'transferpsb' => $datapsb->where('status', 'diterima')->findAll(5),
      'transfer' => $datasantri->findAll(5),
      'transferalumni' => $dataalumni->where('kelas', 'lulus')->findAll(5),
      'reminderSantri' => $datasantri
        ->where('tunggakanspp >', 0)
        ->orWhere('tunggakandu >', 0)
        ->findAll(),
    ];

    return view('pages/tunggakanadmin', $data);
  }

  public function cariTunggakan() {
    if (!$this->request->isAJAX()) {
      throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }

    $keyword = $this->request->getJSON()->keyword ?? '';

    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $psb = new PsbModel();

    $data = [
      'psb' => $psb->like('nama', $keyword)->where('status', 'diterima')->findAll(5),
      'santri' => $santri->like('nama', $keyword)->findAll(5),
      'alumni' => $alumni->like('nama', $keyword)->where('kelas', 'lulus')->findAll(5),
    ];

    return $this->response->setJSON($data);
  }
  
  public function cariTunggakanadmin() {
    if (!$this->request->isAJAX()) {
      throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }

    $keyword = $this->request->getJSON()->keyword ?? '';

    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $psb = new PsbModel();

    $data = [
      'psb' => $psb->like('nama', $keyword)->where('status', 'diterima')->findAll(5),
      'santri' => $santri->like('nama', $keyword)->findAll(5),
      'alumni' => $alumni->like('nama', $keyword)->where('kelas', 'lulus')->findAll(5),
    ];

    return $this->response->setJSON($data);
  }

  public function reminder()
{
    if ($this->request->isAJAX()) {
        $datasantri = new SantriModel();
        $datapsb    = new PsbModel();
        $transfer   = new TransferModel();

        $reminderSantri = $datasantri
            ->where('tunggakanspp >', 0)
            ->orWhere('tunggakandu >', 0)
            ->orWhere('tunggakantl >', 0)
            ->orderBy('tunggakanspp', 'DESC')
            ->findAll();
            
        foreach ($reminderSantri as &$santri) {
            $last = $transfer->where('nisn', $santri['nisn'])
                             ->orderBy('tanggal', 'DESC')
                             ->first();

            if ($last) {
                $santri['last_payment'] = [
                    'tanggal' => $last['tanggal'],
                    'jumlah'  => $last['saldomasuk'],
                ];
            } else {
                $santri['last_payment'] = null;
            }
        }

        $reminderPsb = $datapsb
            ->where('status', 'diterima')
            ->where('tunggakandu >', 0)
            ->findAll();

        return $this->response->setJSON([
            'santri' => $reminderSantri,
            'psb'    => $reminderPsb,
            'alumni' => [],
        ]);
    }
}

  public function daftarulangBeasiswa() {
    $santriModel = new SantriModel();

    // Ambil input dari form
    $dukelas2 = $this->request->getPost('dukelas2');
    $dukelas3 = $this->request->getPost('dukelas3');

    // Update untuk kelas 8 dan 11 dengan program BEASISWA
    $santriBeasiswaKelas2 = $santriModel
    ->groupStart()
    ->where('kelas', '8')
    ->orWhere('kelas', '11')
    ->groupEnd()
    ->where('program', 'BEASISWA')
    ->findAll();

    foreach ($santriBeasiswaKelas2 as $santri) {
      $tunggakanBaru = $santri['tunggakandu'] + $dukelas2;
      $santriModel->update($santri['nisn'], ['tunggakandu' => $tunggakanBaru]);
    }

    // Update untuk kelas 9 dan 12 dengan program BEASISWA
    $santriBeasiswaKelas3 = $santriModel
    ->groupStart()
    ->where('kelas', '9')
    ->orWhere('kelas', '12')
    ->groupEnd()
    ->where('program', 'BEASISWA')
    ->findAll();

    foreach ($santriBeasiswaKelas3 as $santri) {
      $tunggakanBaru = $santri['tunggakandu'] + $dukelas3;
      $santriModel->update($santri['nisn'], ['tunggakandu' => $tunggakanBaru]);
    }

    // Beri feedback dan redirect
    echo '<script>alert("Proses berhasil dilakukan!");</script>';
    return redirect()->to(base_url());
  }

public function download()
{
    $santri = new \App\Models\SantriModel();
    $alumni = new \App\Models\AlumniModel();
    $psb    = new \App\Models\PsbModel();

    $data = [
        'santri' => $santri->findAll(),
        'alumni' => $alumni->findAll(),
        'psb'    => $psb->where('status', 'diterima')->findAll(),
    ];

    // sementara simple CSV
    $filename = "tunggakan_" . date('Ymd_His') . ".csv";
    $output = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $filename);

    // Header kolom
    fputcsv($output, ['Nama', 'Kelas/Status', 'jenjang', 'tunggakan SPP', 'SPP', 'tunggakan Daftar Ulang 1', 'tunggakan Daftar Ulang 2', 'tunggakan Daftar Ulang 3']);

    foreach ($data['santri'] as $row) {
        fputcsv($output, [$row['nama'], $row['kelas'], $row['jenjang'], $row['tunggakanspp'], $row['spp'], $row['tunggakandu'], $row['tunggakandu2'], $row['tunggakandu3']]);
    }
    foreach ($data['psb'] as $row) {
        fputcsv($output, [$row['nama'], $row['status'], $row['jenjang'], 0, 0, $row['tunggakandu'], 0, 0]);
    }

    fclose($output);
    exit;
}

  public function daftarulangMandiri() {
    $santriModel = new SantriModel();

    // Ambil input dari form
    $dukelas2 = $this->request->getPost('dukelas2mdr');
    $dukelas3 = $this->request->getPost('dukelas3mdr');

    // Update untuk kelas 8 dan 11 dengan program BEASISWA
    $santriMandiriKelas2 = $santriModel
    ->groupStart()
    ->where('kelas', '8')
    ->orWhere('kelas', '11')
    ->groupEnd()
    ->where('program', 'MANDIRI')
    ->findAll();

    foreach ($santriMandiriKelas2 as $santri) {
      $tunggakanBaru = $santri['tunggakandu'] + $dukelas2;
      $santriModel->update($santri['nisn'], ['tunggakandu' => $tunggakanBaru]);
    }

    // Update untuk kelas 9 dan 12 dengan program BEASISWA
    $santriMandiriKelas3 = $santriModel
    ->groupStart()
    ->where('kelas', '9')
    ->orWhere('kelas', '12')
    ->groupEnd()
    ->where('program', 'MANDIRI')
    ->findAll();

    foreach ($santriMandiriKelas3 as $santri) {
      $tunggakanBaru = $santri['tunggakandu'] + $dukelas3;
      $santriModel->update($santri['nisn'], ['tunggakandu' => $tunggakanBaru]);
    }

    // Beri feedback dan redirect
    echo '<script>alert("Proses berhasil dilakukan!");</script>';
    return redirect()->to(base_url());
  }
  
    // ==================================================
    // 1. LOAD TUNGGAKAN BY TIPE  (dipakai Alpine.js fetch)
    // ==================================================
    public function load($tipe)
    {
        // Validasi tipe
        $allowed = [
            'spp' => 'tunggakanspp',
            'du'  => 'tunggakandu',
            'du2' => 'tunggakandu2',
            'du3' => 'tunggakandu3',
            'tl'  => 'tunggakantl',
        ];

        if (!array_key_exists($tipe, $allowed)) {
            return $this->failNotFound("Tipe tunggakan tidak valid.");
        }

        $field = $allowed[$tipe];

        // Ambil data: NISN, nama, dan tunggakan terkait
        $data = $this->santri
            ->select("nisn, nama, $field")
            ->orderBy('nama', 'ASC')
            ->findAll();

        return $this->respond($data, 200);
    }

    // ==================================================
    // 2. UPDATE TUNGGAKAN (POST JSON)
    // ==================================================
    public function update()
    {
        $payload = $this->request->getJSON(true);

        if (!$payload || !isset($payload['nisn']) || !isset($payload['kolom']) || !isset($payload['nilai'])) {
            return $this->failValidationError("Format JSON tidak lengkap.");
        }

        // Payload contoh dari Alpine.js:
        // { "nisn": "12345", "kolom": "tunggakanspp", "nilai": "300000" }

        $nisn  = $payload['nisn'];
        $kolom = $payload['kolom'];
        $nilai = $payload['nilai'];

        // Pastikan kolom memang diperbolehkan
        $allowedColumns = [
            'tunggakanspp', 'tunggakandu', 'tunggakandu2', 'tunggakandu3', 'tunggakantl'
        ];

        if (!in_array($kolom, $allowedColumns)) {
            return $this->failValidationError("Kolom tidak diizinkan.");
        }

        // Update
        $updateData = [$kolom => $nilai];

        if ($this->santri->update($nisn, $updateData)) {
            return $this->respond([
                'status' => 'ok',
                'message' => 'Data tunggakan diperbarui.',
                'nisn' => $nisn,
                'kolom' => $kolom,
                'nilai' => $nilai
            ], 200);
        }

        return $this->fail("Gagal update data.");
    }

}