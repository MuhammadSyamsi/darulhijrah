<?php

namespace App\Controllers;

use App\Models\TransferModel;
use App\Models\SantriModel;
use App\Models\PsbModel;
use App\Models\DetailModel;

class Home extends BaseController
{
    public function index()
    {
            return redirect()->to("/beranda");
    }
    public function beranda()
    {
        $sumTransaksi = new TransferModel();
        $sumTunggakan = new SantriModel();
        $psbmodel = new PsbModel();
        $detailmodel = new DetailModel();

        $resultung = $sumTunggakan;
        $resulSum = $sumTransaksi;
        $psb = $psbmodel;
        $detail = $detailmodel;

        $temptung = $sumTunggakan->where("program", "mandiri")->findAll();

        $grandTotal = $this->hitungtunggakan($temptung);
        $tahunini = date("Y");
        $data = [
            "tunggakan" => $resultung
                ->select(
                    "*, (tunggakandu + tunggakantl + tunggakanspp) as totaltung"
                )
                ->orderBy("totaltung", "desc")
                ->findAll(10),
            "sumtung" => $grandTotal,
            "detailtung" => $resultung
                ->select(
                    "*, sum(tunggakandu) as tungdu, sum(tunggakantl) as tungtl, sum(tunggakanspp) as tungspp"
                )
                ->where("program", "mandiri")
                ->findAll(),
            "detailbea" => $resultung
                ->select(
                    "*, sum(tunggakandu) as tungdu, sum(tunggakantl) as tungtl"
                )
                ->where("program", "beasiswa")
                ->findAll(),
            "psb" => $psb
                ->select(
                    "*, count(status) as jumlah, sum(tunggakandu) as totaltunggakan, sum(daftarulang) as kewajiban, (sum(daftarulang) - sum(tunggakandu)) as pembayaran"
                )
                ->groupBy("status")
                ->where("status", "diterima")                
                ->findAll(),
        ];
        return view("pages/home", $data);
    }

    protected function hitungtunggakan($temptung)
    {
        $grandTotal = 0;
        foreach ($temptung as $row) {
            $grandTotal +=
                $row["tunggakandu"] +
                $row["tunggakantl"] +
                $row["tunggakanspp"];
        }

        return $grandTotal;
    }
    
    public function psbCompare()
    {
        $detail = new DetailModel();
    
        // Filter 1
        $f1_start = $this->request->getGet('f1_start'); // format: YYYY-MM
        $f1_end   = $this->request->getGet('f1_end');
    
        // Filter 2
        $f2_start = $this->request->getGet('f2_start');
        $f2_end   = $this->request->getGet('f2_end');
    
        // Konversi ke tanggal
        $f1_start_date = $f1_start ? $f1_start . '-01' : null;
        $f1_end_date   = $f1_end ? date('Y-m-t', strtotime($f1_end . '-01')) : null;
    
        $f2_start_date = $f2_start ? $f2_start . '-01' : null;
        $f2_end_date   = $f2_end ? date('Y-m-t', strtotime($f2_end . '-01')) : null;
    
        // ======================
        // FILTER 1
        // ======================
        $filter1 = $detail
            ->select('SUM(daftarulang) as total, COUNT(id) as jumlah')
            ->where('program', 'PSB')
            ->whereIn('rekening', ['BSI', 'Muamalat Salam', 'Muamalat Yatim', 'Jatim Syariah'])
            ->where('tanggal >=', $f1_start_date)
            ->where('tanggal <=', $f1_end_date)
            ->first();
    
        // FILTER 2
        $filter2 = $detail
            ->select('SUM(daftarulang) as total, COUNT(id) as jumlah')
            ->where('program', 'PSB')
            ->whereIn('rekening', ['BSI', 'Muamalat Salam', 'Muamalat Yatim', 'Jatim Syariah'])
            ->where('tanggal >=', $f2_start_date)
            ->where('tanggal <=', $f2_end_date)
            ->first();
    
        return $this->response->setJSON([
            'filter1' => [
                'total'  => (int) ($filter1['total'] ?? 0),
                'jumlah' => (int) ($filter1['jumlah'] ?? 0),
            ],
            'filter2' => [
                'total'  => (int) ($filter2['total'] ?? 0),
                'jumlah' => (int) ($filter2['jumlah'] ?? 0),
            ],
        ]);
    }

    public function sppCompare()
    {
        $detail = new DetailModel();
    
        // Filter 1
        $f1_start = $this->request->getGet('f1_start');
        $f1_end   = $this->request->getGet('f1_end');
    
        // Filter 2
        $f2_start = $this->request->getGet('f2_start');
        $f2_end   = $this->request->getGet('f2_end');
    
        $f1_start_date = $f1_start ? $f1_start . '-01' : null;
        $f1_end_date   = $f1_end ? date('Y-m-t', strtotime($f1_end)) : null;
    
        $f2_start_date = $f2_start ? $f2_start . '-01' : null;
        $f2_end_date   = $f2_end ? date('Y-m-t', strtotime($f2_end)) : null;
    
        // ================= FILTER 1 =================
        $f1 = $detail->select("
                SUM(tunggakan_spp) as tunggakan,
                SUM(spp) as spp,
                SUM(inden_spp) as inden,
                COUNT(id) as jumlah
            ")
            ->where('tanggal >=', $f1_start_date)
            ->where('tanggal <=', $f1_end_date)
            ->whereIn('rekening', ['BSI', 'Muamalat Salam', 'Muamalat Yatim', 'Jatim Syariah'])
            ->first();
    
        // ================= FILTER 2 =================
        $f2 = $detail->select("
                SUM(tunggakan_spp) as tunggakan,
                SUM(spp) as spp,
                SUM(inden_spp) as inden,
                COUNT(id) as jumlah
            ")
            ->where('tanggal >=', $f2_start_date)
            ->where('tanggal <=', $f2_end_date)
            ->whereIn('rekening', ['BSI', 'Muamalat Salam', 'Muamalat Yatim', 'Jatim Syariah'])
            ->first();
    
        $f1_total = ($f1['tunggakan'] ?? 0) + ($f1['spp'] ?? 0) + ($f1['inden'] ?? 0);
        $f2_total = ($f2['tunggakan'] ?? 0) + ($f2['spp'] ?? 0) + ($f2['inden'] ?? 0);
    
        return $this->response->setJSON([
            'f1' => [
                'tunggakan' => (int) $f1['tunggakan'],
                'spp'       => (int) $f1['spp'],
                'inden'     => (int) $f1['inden'],
                'total'     => (int) $f1_total,
                'jumlah'    => (int) $f1['jumlah'],
            ],
            'f2' => [
                'tunggakan' => (int) $f2['tunggakan'],
                'spp'       => (int) $f2['spp'],
                'inden'     => (int) $f2['inden'],
                'total'     => (int) $f2_total,
                'jumlah'    => (int) $f2['jumlah'],
            ]
        ]);
    }

    public function tentang()
    {
        return view("layouts/tentang");
    }

    public function musrif()
    {
        $santri = new SantriModel();
        $data = [
            "santri" => $santri->findAll(5),
            "mts" => $santri
                ->select("sum(saku) as sa")
                ->where("jenjang", "MTs")
                ->first(),
            "ma" => $santri
                ->select("sum(saku) as sa")
                ->where("jenjang", "MA")
                ->first(),
        ];
        return view("musrif/home", $data);
    }

    public function check()
    {
        // Ambil data POST
        $id = $this->request->getPost("id");
        $nama = $this->request->getPost("nama");
        $kelas = $this->request->getPost("kelas");
        $saku = $this->request->getPost("saku");
        $hp = $this->request->getPost("hp");

        // Load model (pastikan model sudah dibuat)
        $santriModel = new SantriModel();

        // Update data santri
        $santriModel->update($id, [
            "nama" => $nama,
            "kelas" => $kelas,
            "saku" => $saku,
            "hp" => $hp,
        ]);

        // Redirect dengan pesan sukses
        return redirect()
            ->to("/musrif")
            ->with("message", "Data santri berhasil diperbarui");
    }

    public function search()
    {
        $keyword = $this->request->getGet("q");
        $model = new SantriModel();

        $result = $model
            ->like("nama", $keyword)
            ->orLike("kelas", $keyword)
            ->findAll();

        return $this->response->setJSON($result);
    }

    public function valCheckin()
    {
        $santri = new SantriModel();

        $data = [
            "mts" => $santri
                ->where("jenjang", "MTs")
                ->where("saku !=", null)
                ->where("saku >", 0)
                ->findAll(),
            "ma" => $santri
                ->where("jenjang", "MA")
                ->where("saku !=", null)
                ->where("saku >", 0)
                ->findAll(),
        ];

        return view("musrif/validasi", $data);
    }
    
    public function koran()
{
    $detailmodel = new DetailModel();
    $transfermodel = new TransferModel();

    $bulan = $this->request->getGet('bulan') ?? date('m');
    $tahun = $this->request->getGet('tahun') ?? date('Y');
    $rekening = $this->request->getGet('rekening'); // filter rekening

    // Rekap per program
    $detailtrans = $detailmodel
        ->select("rekening, program, 
            SUM(daftarulang) as daftarulang, 
            SUM(tunggakan) as tunggakan, 
            SUM(tunggakan_spp) as tunggakan_spp, 
            SUM(spp) as spp, 
            SUM(inden_spp) as inden_spp, 
            SUM(uangsaku) as saku, 
            SUM(infaq) as infaq, 
            SUM(formulir) as formulir")
        ->where("MONTH(tanggal)", $bulan)
        ->where("YEAR(tanggal)", $tahun)
        ->groupBy("rekening, program")
        ->orderBy("rekening")
        ->findAll();
        
    // Rekap harian
    $rekapharian = $transfermodel
        ->select("DATE(tanggal) as tanggal, rekening, SUM(saldomasuk) as total")
        ->where("MONTH(tanggal)", $bulan)
        ->where("YEAR(tanggal)", $tahun);

    if ($rekening) {
        $rekapharian->where("rekening", $rekening);
    }

    $rekapharian = $rekapharian
        ->groupBy("DATE(tanggal), rekening")
        ->orderBy("tanggal, rekening")
        ->findAll();

    // Daftar rekening untuk filter dropdown
    $listRekening = $transfermodel
        ->select("rekening")
        ->orderBy("rekening")
        ->groupBy("rekening")
        ->findColumn("rekening");

// Detail transaksi (rekap per tanggal dari DetailModel)
$detaildata = $detailmodel
    ->select("DATE(tanggal) as tanggal, rekening, 
        SUM(daftarulang + tunggakan + tunggakan_spp + spp + inden_spp + uangsaku + infaq + formulir) as total")
    ->where("MONTH(tanggal)", $bulan)
    ->where("YEAR(tanggal)", $tahun);

if ($rekening) {
    $detaildata->where("rekening", $rekening);
}

$detaildata = $detaildata
    ->groupBy("DATE(tanggal), rekening")
    ->orderBy("tanggal", "ASC")
    ->findAll();

    return view("pages/laporan-pemasukan", [
    "bulan" => $bulan,
    "tahun" => $tahun,
    "rekening" => $rekening,
    "detailtrans" => $detailtrans,
    "rekapharian" => $rekapharian,
    "listRekening" => $listRekening,
    "detaildata" => $detaildata, // 🔹 baru
]);
}

public function downloadBulanan()
{
    $bulan = $this->request->getGet('bulan') ?? date('m');
    $tahun = $this->request->getGet('tahun') ?? date('Y');

    $detailmodel = new DetailModel();
    $data = $detailmodel
        ->select("rekening, program, SUM(daftarulang) as daftarulang, SUM(tunggakan) as tunggakan, SUM(spp) as spp, SUM(uangsaku) as saku, SUM(infaq) as infaq, SUM(formulir) as formulir")
        ->where("MONTH(tanggal)", $bulan)
        ->where("YEAR(tanggal)", $tahun)
        ->groupBy("rekening, program")
        ->orderBy("rekening")
        ->findAll();

    $csv = $this->makeCSV($data);

    return $this->response
        ->setHeader('Content-Type', 'text/csv')
        ->setHeader('Content-Disposition', 'attachment; filename="laporan-bulanan-'.$bulan.'-'.$tahun.'.csv"')
        ->setBody($csv);
}

public function downloadHarian()
{
    $bulan = $this->request->getGet('bulan') ?? date('m');
    $tahun = $this->request->getGet('tahun') ?? date('Y');
    $rekening = $this->request->getGet('rekening');

    $detailmodel = new TransferModel();
    $builder = $detailmodel
        ->select("DATE(tanggal) as tanggal, rekening, SUM(saldomasuk) as total")
        ->where("MONTH(tanggal)", $bulan)
        ->where("YEAR(tanggal)", $tahun);

    if ($rekening) {
        $builder->where("rekening", $rekening);
    }

    $data = $builder
        ->groupBy("DATE(tanggal), rekening")
        ->orderBy("tanggal, rekening")
        ->findAll();

    $csv = $this->makeCSV($data);

    return $this->response
        ->setHeader('Content-Type', 'text/csv')
        ->setHeader('Content-Disposition', 'attachment; filename="laporan-harian-'.$bulan.'-'.$tahun.'.csv"')
        ->setBody($csv);
}

public function downloadDetail()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $rekening = $this->request->getGet('rekening');

        $detailmodel = new DetailModel();

        $builder = $detailmodel
            ->select("
                *
            ")
            ->where("MONTH(tanggal)", $bulan)
            ->where("YEAR(tanggal)", $tahun);

        if ($rekening) {
            $builder->where("rekening", $rekening);
        }

        $builder->orderBy("tanggal ASC");

        $data = $builder->findAll();

        $csv = $this->makeCSV($data);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="laporan-detail-'.$bulan.'-'.$tahun.($rekening ? "-$rekening" : "").'.csv"')
            ->setBody($csv);
    }

private function makeCSV(array $data): string
{
    // kalau data kosong, return string kosong
    if (empty($data)) {
        return "";
    }

    // ambil header dari key array pertama
    $headers = array_keys($data[0]);

    // buka memory file
    $fp = fopen('php://temp', 'r+');

    // tulis header
    fputcsv($fp, $headers);

    // tulis baris data
    foreach ($data as $row) {
        fputcsv($fp, $row);
    }

    rewind($fp);
    $csv = stream_get_contents($fp);
    fclose($fp);

    return $csv;
}

}
