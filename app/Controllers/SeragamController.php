<?php

namespace App\Controllers;

use App\Models\SeragamSantriModel;
use App\Models\StokSeragamModel;
use App\Models\PengajuanSeragamModel;
use App\Models\DetailPengajuanSeragamModel;
use App\Models\DistribusiSeragamModel;
use App\Models\SantriModel;

class SeragamController extends BaseController
{
    protected $seragamSantri;
    protected $stokSeragam;
    protected $pengajuan;
    protected $detailPengajuan;
    protected $distribusi;
    protected $santri;

    public function __construct()
    {
        $this->seragamSantri   = new SeragamSantriModel();
        $this->stokSeragam     = new StokSeragamModel();
        $this->pengajuan       = new PengajuanSeragamModel();
        $this->detailPengajuan = new DetailPengajuanSeragamModel();
        $this->distribusi      = new DistribusiSeragamModel();
        $this->santri          = new SantriModel(); // sudah kamu buat sebelumnya
    }

    // -----------------------------
    // 1. Tampilkan Data Seragam Santri
    // -----------------------------
    public function index()
    {
        $santriModel = new SantriModel();
        $seragamModel = new SeragamSantriModel();

$kelasList = [
    '7' => 'MTs',
    '8' => 'MTs',
    '9' => 'MTs',
    '10' => 'MA',
    '11' => 'MA',
    '12' => 'MA',
];

$statistikKelas = [];

$jenisKategori = [
    ['baju', 'putih'],
    ['celana', 'abu'],
    ['baju', 'batik'],
    ['celana', 'putih'],
    ['baju', 'coklat'],
    ['celana', 'coklat'],
    ['baju', 'pramuka'],
    ['celana', 'pramuka'],
    ['baju', 'beladiri'],
];

    foreach ($kelasList as $kelas => $jenjang) {
        $santri = $santriModel
            ->where('kelas', $kelas)
            ->where('jenjang', $jenjang)
            ->findAll();

    $itemBelum = [];
    $totalBelum = 0;

    foreach ($jenisKategori as [$jenis, $kategori]) {
        $key = $jenis . '_' . $kategori;
        $itemBelum[$key] = 0;

        foreach ($santri as $s) {
            $nisn = $s['nisn'];

            $count = $seragamModel
                ->where('nisn', $nisn)
                ->where('jenis_seragam', $jenis)
                ->where('kategori', $kategori)
                ->where('status !=', 'sudah_diberikan')
                ->countAllResults();

            $itemBelum[$key] += $count;
            $totalBelum += $count;
        }
    }

    $statistikKelas[] = [
        'kelas' => $kelas,
        'jenjang' => $jenjang,
        'total_belum' => $totalBelum,
        'item_belum' => $itemBelum,
    ];
}    
    // Ambil filter dari query string
        $filterJenjang = $this->request->getGet('jenjang');
        $filterKelas   = $this->request->getGet('kelas');

        // Ambil daftar kelas (distinct)
        $daftar_kelas = $santriModel->select('kelas')->distinct()->orderBy('kelas')->findAll();
        $daftar_kelas = array_column($daftar_kelas, 'kelas');

        // Ambil data santri sesuai filter
        $santriQuery = $santriModel;
        if ($filterJenjang) {
            $santriQuery = $santriQuery->where('jenjang', $filterJenjang);
        }
        if ($filterKelas) {
            $santriQuery = $santriQuery->where('kelas', $filterKelas);
        }

        $santriList = $santriQuery->orderBy('nama')->findAll();

        // Ambil semua data seragam untuk nisn-nisn tersebut
        $nisnList = array_column($santriList, 'nisn');
        $semuaSeragam = [];

        if (!empty($nisnList)) {
            $semuaSeragam = $seragamModel
                ->whereIn('nisn', $nisnList)
                ->findAll();
        }

        // Group data seragam per nisn
        $groupedSeragam = [];
        foreach ($semuaSeragam as $s) {
            $groupedSeragam[$s['nisn']][] = $s;
        }

        // Gabungkan ke data santri
        $santri = [];
        foreach ($santriList as $s) {
            $s['seragam'] = $groupedSeragam[$s['nisn']] ?? [];
            $santri[] = $s;
        }

        return view('seragam/index', [
            'title' => 'Seragam Santri',
            'santri' => $santri,
            'daftar_kelas' => $daftar_kelas,
            'filter_jenjang' => $filterJenjang,
            'filter_kelas' => $filterKelas,
            'statistikKelas' => $statistikKelas,
        ]);
    }

    // -----------------------------
    // 2. Tambah/Update Seragam Santri
    // -----------------------------
    public function simpanSeragam()
    {
        $this->seragamSantri->save([
            'id'            => $this->request->getPost('id'),
            'nisn'          => $this->request->getPost('nisn'),
            'jenis_seragam' => $this->request->getPost('jenis_seragam'),
            'kategori'      => $this->request->getPost('kategori'),
            'ukuran'        => $this->request->getPost('ukuran'),
            'status'        => 'belum_diberikan'
        ]);

        return redirect()->back()->with('success', 'Data seragam disimpan');
    }

    // -----------------------------
    // 3. Tampilkan Pengajuan Seragam
    // -----------------------------
    public function pengajuan()
    {
        $data['pengajuan'] = $this->pengajuan->orderBy('tanggal', 'DESC')->findAll();

        return view('seragam/pengajuan', $data);
    }

    public function simpanPengajuan()
    {
        $pengajuanId = $this->pengajuan->insert([
            'tanggal' => date('Y-m-d'),
            'status'  => 'diajukan',
            'catatan' => $this->request->getPost('catatan')
        ], true); // return insertID

        $detail = $this->request->getPost('detail'); // array of [jenis_seragam, kategori, ukuran, jumlah]

        foreach ($detail as $item) {
            $this->detailPengajuan->insert([
                'pengajuan_id'  => $pengajuanId,
                'jenis_seragam' => $item['jenis_seragam'],
                'kategori'      => $item['kategori'],
                'ukuran'        => $item['ukuran'],
                'jumlah'        => $item['jumlah']
            ]);
        }

        return redirect()->to('/seragam/pengajuan')->with('success', 'Pengajuan berhasil dibuat');
    }

    // -----------------------------
    // 4. Distribusi Seragam ke Santri
    // -----------------------------
    public function distribusi()
    {
        $data['belum_diberikan'] = $this->seragamSantri
            ->select('seragam_santri.*, santri.nama')
            ->join('santri', 'santri.nisn = seragam_santri.nisn')
            ->where('seragam_santri.status', 'belum_diberikan')
            ->findAll();

        return view('seragam/distribusi', $data);
    }

    public function simpanDistribusi()
    {
        $nisn          = $this->request->getPost('nisn');
        $jenis         = $this->request->getPost('jenis_seragam');
        $kategori      = $this->request->getPost('kategori');
        $ukuran        = $this->request->getPost('ukuran');

        // Simpan distribusi
        $this->distribusi->insert([
            'nisn'              => $nisn,
            'jenis_seragam'     => $jenis,
            'kategori'          => $kategori,
            'ukuran'            => $ukuran,
            'tanggal_distribusi'=> date('Y-m-d'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

        // Update status seragam_santri
        $this->seragamSantri
            ->where([
                'nisn'          => $nisn,
                'jenis_seragam' => $jenis,
                'kategori'      => $kategori,
                'ukuran'        => $ukuran,
            ])
            ->set(['status' => 'sudah_diberikan'])
            ->update();

        return redirect()->back()->with('success', 'Distribusi berhasil dicatat');
    }

    // -----------------------------
    // 5. Cek dan Update Stok Seragam
    // -----------------------------
    public function stok()
    {
        $data['stok'] = $this->stokSeragam->orderBy('jenis_seragam')->findAll();
        return view('seragam/stok', $data);
    }

    public function updateStok()
    {
        $this->stokSeragam->save([
            'id'            => $this->request->getPost('id'), // null for insert
            'jenis_seragam' => $this->request->getPost('jenis_seragam'),
            'kategori'      => $this->request->getPost('kategori'),
            'ukuran'        => $this->request->getPost('ukuran'),
            'jumlah'        => $this->request->getPost('jumlah'),
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diperbarui');
    }
    
    public function update()
    {
        $nisn = $this->request->getPost('nisn');
        $input = $this->request->getPost('seragam');
    
        $seragamModel = new SeragamSantriModel();
    
        // Hapus seragam lama santri ini
        $seragamModel->where('nisn', $nisn)->delete();
    
        $dataInsert = [];
    
        foreach ($input as $jenis => $kategoriData) {
            foreach ($kategoriData as $kategori => $detail) {
                if (!empty($detail['ukuran'])) {
                    $dataInsert[] = [
                        'nisn' => $nisn,
                        'jenis_seragam' => $jenis,
                        'kategori' => $kategori,
                        'ukuran' => $detail['ukuran'],
                        'status' => $detail['status'] ?? 'belum',
                    ];
                }
            }
        }
    
        if (!empty($dataInsert)) {
            $seragamModel->insertBatch($dataInsert);
        }
    
        return redirect()->back()->with('success', 'Data seragam berhasil diperbarui.');
    }
    
    public function downloadcsv()
{
    $santriList = $this->santri->orderBy('jenjang')->orderBy('kelas')->orderBy('nama')->findAll();

    $nisnList = array_column($santriList, 'nisn');

    $seragamList = [];
    if (!empty($nisnList)) {
        $seragamList = $this->seragamSantri
            ->whereIn('nisn', $nisnList)
            ->findAll();
    }

    // Kelompokkan data seragam berdasarkan NISN
    $grouped = [];
    foreach ($seragamList as $s) {
        $grouped[$s['nisn']][] = $s;
    }

    // Header CSV
    $header = [
        'NISN', 'Nama', 'Jenjang', 'Kelas',
        'Baju Putih', 'Celana Abu', 'Baju Batik', 'Celana Putih',
        'Baju Coklat', 'Celana Coklat', 'Baju Pandu', 'Celana Pandu',
        'Baju Beladiri', 'Total Belum'
    ];

    $rows = [];
    foreach ($santriList as $s) {
        $map = [];
        foreach ($grouped[$s['nisn']] ?? [] as $item) {
            $key = $item['jenis_seragam'] . '-' . $item['kategori'];
            $map[$key] = $item;
        }

        $row = [
            $s['nisn'],
            $s['nama'],
            $s['jenjang'],
            $s['kelas'],
        ];

        $itemList = [
            'baju-putih', 'celana-abu',
            'baju-batik', 'celana-putih',
            'baju-coklat', 'celana-coklat',
            'baju-pandu', 'celana-pandu',
            'baju-beladiri'
        ];

        $totalBelum = 0;

        foreach ($itemList as $key) {
            if (isset($map[$key])) {
                if ($map[$key]['status'] == 'sudah_diberikan') {
                    $row[] = 'Sudah (' . $map[$key]['ukuran'] . ')';
                } else {
                    $row[] = 'Belum';
                    $totalBelum++;
                }
            } else {
                $row[] = '-';
                $totalBelum++;
            }
        }

        $row[] = $totalBelum; // Tambahkan kolom total belum
        $rows[] = $row;
    }

    // Output CSV
    $filename = 'Rekap_Seragam_Semua_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen('php://output', 'w');
    fputcsv($output, $header);

    foreach ($rows as $r) {
        fputcsv($output, $r);
    }

    fclose($output);
    exit;
}
    
}