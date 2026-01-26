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
            "jumlah" => $resulSum
                ->select("sum(saldomasuk) as sum")
                ->where("month(tanggal) = month(CURRENT_DATE)")
                ->where("YEAR(tanggal) = YEAR(CURRENT_DATE)")
                ->findAll(),
            "jumlahrekening" => $resulSum
                ->select(
                    "YEAR(tanggal) as tahun, MONTHNAME(tanggal) as bulan, sum(saldomasuk) as sum, rekening"
                )
                ->groupBy("rekening, tahun, bulan")
                ->orderBy("tahun", "desc")
                ->where("month(tanggal) = month(CURRENT_DATE)")
                ->where("YEAR(tanggal) = $tahunini")
                ->findAll(),
            "bulanini" => $resulSum
                ->select(
                    "YEAR(tanggal) as tahun, tanggal, sum(saldomasuk) as sum"
                )
                ->where("month(tanggal) = month(CURRENT_DATE)")
                ->where("YEAR(tanggal) = YEAR(CURRENT_DATE)")
                // ->where("YEAR(tanggal) = $tahunini")
                ->groupBy("tahun")
                ->orderBy("tahun", "desc")
                ->findAll(1),
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
                ->findAll(),
            "detailtrans" => $detail
                ->select(
                    "*, rekening, program, sum(daftarulang) as daftarulang, sum(tunggakan) as tunggakan, sum(spp) as spp, sum(uangsaku) as saku, sum(infaq) as infaq, sum(formulir) as formulir"
                )
                ->groupBy("rekening, program")
                ->orderBy("rekening")
                ->where("month(tanggal) = month(CURRENT_DATE)")
                ->where("YEAR(tanggal) = YEAR(CURRENT_DATE)")
                ->findAll(),
            "detailtranslalu" => $detail
                ->select(
                    "*, rekening, program, sum(daftarulang) as daftarulang, sum(tunggakan) as tunggakan, sum(spp) as spp, sum(uangsaku) as saku, sum(infaq) as infaq, sum(formulir) as formulir"
                )
                ->groupBy("rekening, program")
                ->orderBy("rekening")
                ->where("month(tanggal) = month(CURRENT_DATE) - 1")
                ->where("YEAR(tanggal) = YEAR(CURRENT_DATE)")
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
            SUM(spp) as spp, 
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
        SUM(daftarulang + tunggakan + spp + uangsaku + infaq + formulir) as total")
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
