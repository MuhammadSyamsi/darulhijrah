<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\HariModel;
use App\Models\JadwalPelajaranModel;
use App\Models\GuruMapelModel;
use App\Models\SlotPelajaranModel;

use App\Libraries\PdfGenerator;

class Guru extends BaseController
{
    protected $guruModel;

    public function __construct()
    {
        $this->guruModel        = new GuruModel();
        $this->mapelModel       = new MapelModel();
        $this->kelasModel       = new KelasModel();
        $this->hariModel        = new HariModel();
        $this->jadwalModel      = new JadwalPelajaranModel();
        $this->guruMapelModel   = new GuruMapelModel();
        $this->slotModel        = new SlotPelajaranModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Guru',
            'guruList' => $this->guruModel->findAll(),
            'matpel' => $this->mapelModel->findAll(),
            'guruMapel' => $this->guruMapelModel
                ->select('guru_mapel_mingguan.id, guru.nama AS nama_guru, mapel.nama_mapel, guru_mapel_mingguan.jumlah_jam')
                ->join('guru', 'guru.id = guru_mapel_mingguan.guru_id')
                ->join('mapel', 'mapel.id = guru_mapel_mingguan.mapel_id')
                ->findAll()
        ];

        return view('guru/insert', $data);
    }

    public function mapel()
    {
        $data = [
            'title' => 'Data Mata Pelajaran',
            'guruList' => $this->guruModel->findAll(),
            'matpel' => $this->mapelModel->findAll(),
            'guruMapel' => $this->guruMapelModel
                ->select('guru_mapel_mingguan.id, guru.nama AS nama_guru, mapel.nama_mapel, guru_mapel_mingguan.jumlah_jam')
                ->join('guru', 'guru.id = guru_mapel_mingguan.guru_id')
                ->join('mapel', 'mapel.id = guru_mapel_mingguan.mapel_id')
                ->findAll()
        ];

        return view('guru/mapel', $data);
    }

    public function kelas()
    {
        $data = [
            'title' => 'Data Kelas',
            'kelas' => $this->kelasModel->findAll()
        ];

        return view('guru/kelas', $data);
    }

    public function save()
    {
        $this->guruModel->save([
            'id' => $this->request->getPost('id'),
            'nama' => $this->request->getPost('nama'),
            'nip' => $this->request->getPost('nip'),
            'mapel' => $this->request->getPost('mapel'),
            'jabatan' => $this->request->getPost('jabatan'),
            'kelas' => $this->request->getPost('kelas'),
            'pendidikan_akhir' => $this->request->getPost('pendidikan_akhir'),
        ]);

        return redirect()->to('/guru')->with('success', 'Data guru berhasil disimpan.');
    }

    public function delete($id)
    {
        $this->guruModel->delete($id);
        return redirect()->to('/guru')->with('success', 'Data guru berhasil dihapus.');
    }

    public function delete_kelas($id)
    {
        $this->kelasModel->delete($id);
        return redirect()->to('/guru')->with('success', 'Data kelas berhasil dihapus.');
    }

    public function edit($id)
    {
        $guru = $this->guruModel->find($id);

        if (!$guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Guru tidak ditemukan');
        }

        return view('guru/edit', ['guru' => $guru]);
    }

    public function update($id)
    {
        $this->guruModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'nip' => $this->request->getPost('nip'),
            'mapel' => $this->request->getPost('mapel'),
            'jabatan' => $this->request->getPost('jabatan'),
            'kelas' => $this->request->getPost('kelas'),
            'pendidikan_akhir' => $this->request->getPost('pendidikan_akhir'),
        ]);

        return redirect()->to('/guru')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function jadwal()
    {
        $jadwal = $this->jadwalModel
            ->select('jadwal_pelajaran.id, kelas.nama_kelas, kelas.tingkat, hari.nama_hari, slot_pelajaran.jam_ke, guru.nama AS nama_guru, mapel.nama_mapel')
            ->join('slot_pelajaran', 'slot_pelajaran.id = jadwal_pelajaran.slot_id')
            ->join('hari', 'hari.id = slot_pelajaran.hari_id')
            ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
            ->join('guru', 'guru.id = jadwal_pelajaran.guru_id')
            ->join('mapel', 'mapel.id = jadwal_pelajaran.mapel_id')
            ->orderBy('hari.id')
            ->orderBy('slot_pelajaran.jam_ke')
            ->orderBy('kelas.nama_kelas')
            ->findAll();

        // Proses pengelompokan
        $jadwalGrouped = [];
        $kelasList = [];

foreach ($jadwal as $item) {
    $hari = $item['nama_hari'];
    $jam = $item['jam_ke'];
    $kelas = $item['nama_kelas'];
    $tingkat = $item['tingkat'];
    $labelKelas = $kelas . ' - ' . $tingkat; // ← ini yang akan jadi key unik & label tabel

    if (!in_array($labelKelas, $kelasList)) {
        $kelasList[] = $labelKelas;
    }

    $jadwalGrouped[$hari][$jam][$labelKelas] = [
        'id'          => $item['id'],
        'nama_mapel'  => $item['nama_mapel'],
        'nama_guru'   => $item['nama_guru'],
        'tingkat'     => $tingkat,
        'nama_kelas'  => $kelas,
    ];
}

        sort($kelasList); // agar konsisten urutan kolom
        // Ambil daftar kelas unik dari slot_pelajaran
$kelasChecklist = $this->slotModel
    ->select('kelas.id, kelas.nama_kelas, kelas.tingkat')
    ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
    ->groupBy('kelas.id')
    ->orderBy('kelas.nama_kelas')
    ->orderBy('kelas.tingkat')
    ->findAll();
    
// Ambil semua jadwal yang sudah diisi
$jadwalTerisi = $this->jadwalModel
    ->select('slot_pelajaran.kelas_id, slot_pelajaran.hari_id, slot_pelajaran.jam_ke')
    ->join('slot_pelajaran', 'slot_pelajaran.id = jadwal_pelajaran.slot_id')
    ->findAll();

// Jadikan array 3 dimensi agar mudah dicek di view
$slotTerisi = [];
foreach ($jadwalTerisi as $j) {
  $slotTerisi[$j['kelas_id']][$j['hari_id']][$j['jam_ke']] = true;
}

        $data = [
            'slotTerisi' => $slotTerisi,
            'title' => 'Jadwal Pelajaran',
            'guruList' => $this->guruModel->findAll(),
            'matpel' => $this->mapelModel->findAll(),
            'guruMapel' => $this->guruMapelModel
                ->select('guru_mapel_mingguan.id, guru.nama AS nama_guru, mapel.nama_mapel, guru_mapel_mingguan.jumlah_jam')
                ->join('guru', 'guru.id = guru_mapel_mingguan.guru_id')
                ->join('mapel', 'mapel.id = guru_mapel_mingguan.mapel_id')
                ->findAll(),
            'kelas' => $this->kelasModel->findAll(),
            'hari' => $this->hariModel->findAll(),
            'slot_pelajaran' => $this->slotModel
                ->select('slot_pelajaran.id, kelas.nama_kelas, hari.nama_hari, slot_pelajaran.jam_ke')
                ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
                ->join('hari', 'hari.id = slot_pelajaran.hari_id')
                ->findAll(),
            'jadwal' => $jadwal, // original
            'jadwalGrouped' => $jadwalGrouped, // untuk tampilan per hari
            'kelasList' => $kelasList, // untuk header kolom
            'kelasChecklist' => $kelasChecklist,
        ];

        return view('guru/jadwal', $data);
    }

    public function lihat_jadwal()
    {
        $jadwal = $this->jadwalModel
            ->select('jadwal_pelajaran.id, kelas.nama_kelas, kelas.tingkat, hari.nama_hari, slot_pelajaran.jam_ke, guru.nama AS nama_guru, mapel.nama_mapel')
            ->join('slot_pelajaran', 'slot_pelajaran.id = jadwal_pelajaran.slot_id')
            ->join('hari', 'hari.id = slot_pelajaran.hari_id')
            ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
            ->join('guru', 'guru.id = jadwal_pelajaran.guru_id')
            ->join('mapel', 'mapel.id = jadwal_pelajaran.mapel_id')
            ->orderBy('hari.id')
            ->orderBy('slot_pelajaran.jam_ke')
            ->orderBy('kelas.nama_kelas')
            ->findAll();

        // Proses pengelompokan
        $jadwalGrouped = [];
        $kelasList = [];

foreach ($jadwal as $item) {
    $hari = $item['nama_hari'];
    $jam = $item['jam_ke'];
    $kelas = $item['nama_kelas'];
    $tingkat = $item['tingkat'];
    $labelKelas = $kelas . ' - ' . $tingkat; // ← ini yang akan jadi key unik & label tabel

    if (!in_array($labelKelas, $kelasList)) {
        $kelasList[] = $labelKelas;
    }

    $jadwalGrouped[$hari][$jam][$labelKelas] = [
        'nama_mapel'  => $item['nama_mapel'],
        'nama_guru'   => $item['nama_guru'],
        'tingkat'     => $tingkat,
        'nama_kelas'  => $kelas,
    ];
}

        sort($kelasList); // agar konsisten urutan kolom

        $data = [
            'title' => 'Jadwal Mengajar MTs DH 2 Pasuruan',
            'guruList' => $this->guruModel->findAll(),
            'matpel' => $this->mapelModel->findAll(),
            'guruMapel' => $this->guruMapelModel
                ->select('guru_mapel_mingguan.id, guru.nama AS nama_guru, mapel.nama_mapel, guru_mapel_mingguan.jumlah_jam')
                ->join('guru', 'guru.id = guru_mapel_mingguan.guru_id')
                ->join('mapel', 'mapel.id = guru_mapel_mingguan.mapel_id')
                ->findAll(),
            'kelas' => $this->kelasModel->findAll(),
            'hari' => $this->hariModel->findAll(),
            'slot_pelajaran' => $this->slotModel
                ->select('slot_pelajaran.id, kelas.nama_kelas, hari.nama_hari, slot_pelajaran.jam_ke')
                ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
                ->join('hari', 'hari.id = slot_pelajaran.hari_id')
                ->findAll(),
            'jadwal' => $jadwal, // original
            'jadwalGrouped' => $jadwalGrouped, // untuk tampilan per hari
            'kelasList' => $kelasList, // untuk header kolom
        ];
        

        return view('guru/view-jadwal', $data);
    }
    public function cetak_jadwal()
    {
        $jadwal = $this->jadwalModel
            ->select('jadwal_pelajaran.id, kelas.nama_kelas, kelas.tingkat, hari.nama_hari, slot_pelajaran.jam_ke, guru.nama AS nama_guru, mapel.nama_mapel')
            ->join('slot_pelajaran', 'slot_pelajaran.id = jadwal_pelajaran.slot_id')
            ->join('hari', 'hari.id = slot_pelajaran.hari_id')
            ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
            ->join('guru', 'guru.id = jadwal_pelajaran.guru_id')
            ->join('mapel', 'mapel.id = jadwal_pelajaran.mapel_id')
            ->orderBy('hari.id')
            ->orderBy('slot_pelajaran.jam_ke')
            ->orderBy('kelas.nama_kelas')
            ->findAll();

        // Proses pengelompokan
        $jadwalGrouped = [];
        $kelasList = [];

foreach ($jadwal as $item) {
    $hari = $item['nama_hari'];
    $jam = $item['jam_ke'];
    $kelas = $item['nama_kelas'];
    $tingkat = $item['tingkat'];
    $labelKelas = $kelas . ' - ' . $tingkat; // ← ini yang akan jadi key unik & label tabel

    if (!in_array($labelKelas, $kelasList)) {
        $kelasList[] = $labelKelas;
    }

    $jadwalGrouped[$hari][$jam][$labelKelas] = [
        'nama_mapel'  => $item['nama_mapel'],
        'nama_guru'   => $item['nama_guru'],
        'tingkat'     => $tingkat,
        'nama_kelas'  => $kelas,
    ];
}

        sort($kelasList); // agar konsisten urutan kolom

        $data = [
            'title' => 'Jadwal Mengajar MTs DH 2 Pasuruan',
            'guruList' => $this->guruModel->findAll(),
            'matpel' => $this->mapelModel->findAll(),
            'guruMapel' => $this->guruMapelModel
                ->select('guru_mapel_mingguan.id, guru.nama AS nama_guru, mapel.nama_mapel, guru_mapel_mingguan.jumlah_jam')
                ->join('guru', 'guru.id = guru_mapel_mingguan.guru_id')
                ->join('mapel', 'mapel.id = guru_mapel_mingguan.mapel_id')
                ->findAll(),
            'kelas' => $this->kelasModel->findAll(),
            'hari' => $this->hariModel->findAll(),
            'slot_pelajaran' => $this->slotModel
                ->select('slot_pelajaran.id, kelas.nama_kelas, hari.nama_hari, slot_pelajaran.jam_ke')
                ->join('kelas', 'kelas.id = slot_pelajaran.kelas_id')
                ->join('hari', 'hari.id = slot_pelajaran.hari_id')
                ->findAll(),
            'jadwal' => $jadwal, // original
            'jadwalGrouped' => $jadwalGrouped, // untuk tampilan per hari
            'kelasList' => $kelasList, // untuk header kolom
        ];

        return view('guru/cetak-jadwal', $data);
    }
    
    public function api_cek_jadwal_by_guru($guruId)
{
    $jadwal = $this->jadwalModel
        ->select('slot_pelajaran.hari_id, slot_pelajaran.jam_ke')
        ->join('slot_pelajaran', 'slot_pelajaran.id = jadwal_pelajaran.slot_id')
        ->where('guru_id', $guruId)
        ->findAll();

    return $this->response->setJSON($jadwal);
}

public function simpanChecklist()
{
    $guruId = $this->request->getPost('guru_id');
    $mapelId = $this->request->getPost('mapel_id');
    $kelasIds = $this->request->getPost('kelas_id'); // array of selected kelas
    $slots = $this->request->getPost('slots'); // format: [kelas_id => [hari_id => [jam_ke, jam_ke]]]

    if (!$guruId || !$mapelId || !$kelasIds || !$slots) {
        return redirect()->back()->with('error', 'Lengkapi semua data terlebih dahulu.');
    }

    $jumlahTersimpan = 0;

    foreach ($slots as $kelasId => $hariSlot) {
        // Pastikan hanya kelas yang dipilih
        if (!in_array($kelasId, $kelasIds)) {
            continue;
        }

        foreach ($hariSlot as $hariId => $jamList) {
            foreach ($jamList as $jamKe) {

                // Cari slot yang sesuai
                $slot = $this->slotModel->where([
                    'kelas_id' => $kelasId,
                    'hari_id' => $hariId,
                    'jam_ke'   => $jamKe
                ])->first();

                if ($slot) {
                    // Cek duplikat
                    $cek = $this->jadwalModel->where('slot_id', $slot['id'])->first();

                    if (!$cek) {
                        $this->jadwalModel->insert([
                            'guru_id'  => $guruId,
                            'mapel_id' => $mapelId,
                            'slot_id'  => $slot['id']
                        ]);
                        $jumlahTersimpan++;
                    }
                }
            }
        }
    }

    return redirect()->back()->with('success', "$jumlahTersimpan jadwal berhasil disimpan.");
}

    
}