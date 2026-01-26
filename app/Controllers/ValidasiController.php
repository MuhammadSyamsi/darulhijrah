<?php
namespace App\Controllers;

use App\Models\{ValidasiKehadiranModel, JadwalPelajaranModel, SlotPelajaranModel};
use CodeIgniter\Controller;
use Myth\Auth\Entities\User;

class ValidasiController extends Controller
{
    protected $validasiModel;
    protected $jadwalModel;
    protected $slotModel;

    public function __construct()
    {
        $this->validasiModel = new ValidasiKehadiranModel();
        $this->jadwalModel   = new JadwalPelajaranModel();
        $this->slotModel     = new SlotPelajaranModel();
    }

    public function index()
{
    // Ambil tanggal dari query GET, default hari ini
    $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

    // Tentukan hari ke-berapa (1=Senin, 7=Minggu)
    $hariKe = date('N', strtotime($tanggal)); // N: 1=Senin ... 7=Minggu

    // Ambil jadwal sesuai hari
    $jadwalHarian = $this->jadwalModel->getJadwalByHari($hariKe);

    $data = [
        'tanggal' => $tanggal,
        'jadwalHarian' => $jadwalHarian
    ];

    return view('validasi/input', $data);
}
    
    // Form validasi oleh musrif
    public function form($jadwal_id)
    {
        $jadwal = $this->jadwalModel->find($jadwal_id);
        $slot   = $this->slotModel->find($jadwal['slot_id']);

        return view('validasi/form', [
            'jadwal' => $jadwal,
            'slot'   => $slot
        ]);
    }

    // Simpan validasi
    public function simpan()
{
    $data = $this->request->getPost('data');

    if (!$data || !is_array($data)) {
        return redirect()->back()->with('error', 'Data tidak valid');
    }

    foreach ($data as $item) {
        // Skip jika jadwal_id atau tanggal kosong (data tidak lengkap)
        if (empty($item['jadwal_id']) || empty($item['tanggal'])) {
            continue;
        }

        $this->validasiModel->insert([
            'jadwal_id'      => $item['jadwal_id'],
            'musrif_id'      => user_id(),
            'tanggal'        => $item['tanggal'],
            'jam_ke'         => $item['jam_ke'] ?? null,
            'status_hadir'   => $item['status_hadir'] ?? null,
            'kerapian'       => isset($item['kerapian']) && $item['kerapian'] == '1',
            'seragam_sesuai' => isset($item['seragam_sesuai']) && $item['seragam_sesuai'] == '1',
            'catatan'        => $item['catatan'] ?? null
        ]);
    }

    return redirect()->back()->with('success', 'Semua absensi berhasil disimpan');
}

    // Tampilkan Jadwal Harian
    public function jadwalHarian()
    {
        $hariIni = date('w');
        $hariDb = $hariIni == 0 ? 7 : $hariIni;

        $data['jadwalHarian'] = $this->jadwalModel->getJadwalByHari($hariDb);

        return view('validasi/jadwal_harian', $data);
    }

    // Rekap performa guru harian/mingguan/bulanan oleh admin
    public function rekap()
    {
        $builder = $this->validasiModel
            ->select('guru.nama as nama_guru, 
                COUNT(validasi_kehadiran.id) as total, 
                SUM(CASE WHEN status_hadir = "tepat waktu" THEN 1 ELSE 0 END) as tepat,
                SUM(CASE WHEN status_hadir = "terlambat" THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN status_hadir = "tidak hadir" THEN 1 ELSE 0 END) as absen')
            ->join('jadwal_pelajaran', 'jadwal_pelajaran.id = validasi_kehadiran.jadwal_id')
            ->join('guru', 'jadwal_pelajaran.guru_id = guru.id')
            ->groupBy('guru.id')
            ->orderBy('guru.nama', 'ASC');
    
        $periode = $this->request->getGet('periode');
        if ($periode) {
            [$tahun, $bulan] = explode('-', $periode);
            $builder->where('YEAR(validasi_kehadiran.tanggal)', $tahun)
                    ->where('MONTH(validasi_kehadiran.tanggal)', $bulan);
        }
    
        $data = $builder->findAll();
    
        return view('validasi/rekap_performa', [
            'data' => $data,
            'periode' => $periode ?? date('Y-m')
        ]);
    }
    
    public function download()
    {
        $periode = $this->request->getGet('periode');
        [$tahun, $bulan] = $periode ? explode('-', $periode) : [date('Y'), date('m')];
    
        $builder = $this->validasiModel
            ->select('guru.nama as nama_guru, 
                COUNT(validasi_kehadiran.id) as total, 
                SUM(CASE WHEN status_hadir = "tepat waktu" THEN 1 ELSE 0 END) as tepat,
                SUM(CASE WHEN status_hadir = "terlambat" THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN status_hadir = "tidak hadir" THEN 1 ELSE 0 END) as absen')
            ->join('jadwal_pelajaran', 'jadwal_pelajaran.id = validasi_kehadiran.jadwal_id')
            ->join('guru', 'jadwal_pelajaran.guru_id = guru.id')
            ->where('YEAR(validasi_kehadiran.tanggal)', $tahun)
            ->where('MONTH(validasi_kehadiran.tanggal)', $bulan)
            ->groupBy('guru.id')
            ->orderBy('guru.nama', 'ASC')
            ->findAll();
    
        // Contoh export Excel sederhana
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=RekapGuru-{$periode}.xls");
    
        echo "<table border='1'>
                <tr><th>No</th><th>Nama Guru</th><th>Total</th><th>Tepat</th><th>Terlambat</th><th>Absen</th></tr>";
        foreach ($builder as $i => $r) {
            echo "<tr>
                    <td>".($i+1)."</td>
                    <td>{$r['nama_guru']}</td>
                    <td>{$r['total']}</td>
                    <td>{$r['tepat']}</td>
                    <td>{$r['terlambat']}</td>
                    <td>{$r['absen']}</td>
                  </tr>";
        }
        echo "</table>";
    }

}
