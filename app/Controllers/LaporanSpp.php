<?php

namespace App\Controllers;

use App\Models\DetailModel;
use App\Models\TunggakanModel;
use App\Models\SantriModel;
use App\Models\AlumniModel;
use App\Models\PsbModel;

class LaporanSpp extends BaseController
{

public function index()
{

    $detail    = new DetailModel();
    $tunggakan = new TunggakanModel();
    $santriModel = new SantriModel();

    $bulan = [
        '2025-06' => 'Juni 2025',
        '2025-07' => 'Juli 2025',
        '2025-08' => 'Agustus 2025',
        '2025-09' => 'September 2025',
        '2025-10' => 'Oktober 2025',
        '2025-11' => 'November 2025',
        '2025-12' => 'Desember 2025',
        '2026-01' => 'Januari 2026',
        '2026-02' => 'Februari 2026',
        '2026-03' => 'Maret 2026',
        '2026-04' => 'April 2026',
    ];

    $santri = $santriModel
        ->select('nisn,nama,kelas')
        ->findAll();

    foreach ($santri as &$s) {

        $totalBayarSPP = 0;
        $totalBayarTunggakan = 0;
        $totalBayarInden = 0;
        $totalTunggakan = 0;

        foreach ($bulan as $key => $label) {

    [$tahun,$bln] = explode('-',$key);

    // ===== KHUSUS JUNI 2025 =====
    if ($key == '2025-06') {

        $sppBulan = 0;
        $tunggakanBayar = 0;
        $indenBulan = 0;

    } else {

        // ===== PEMBAYARAN =====
        $bayar = $detail
            ->select("
                SUM(COALESCE(detail.spp,0)) AS spp,
                SUM(COALESCE(detail.tunggakan_spp,0)) AS tunggakan,
                SUM(COALESCE(detail.inden_spp,0)) AS inden
            ")
            ->join('transfer','transfer.idtrans = detail.id')
            ->where('transfer.nisn',$s['nisn'])
            ->where('MONTH(detail.tanggal)',$bln)
            ->where('YEAR(detail.tanggal)',$tahun)
            ->first();

        $sppBulan       = $bayar['spp'] ?? 0;
        $tunggakanBayar = $bayar['tunggakan'] ?? 0;
        $indenBulan     = $bayar['inden'] ?? 0;
    }

    // ===== TUNGGAKAN =====
    $tgk = $tunggakan
        ->select('SUM(nominal) AS total')
        ->where('nisn',$s['nisn'])
        ->where('MONTH(tanggal)',$bln)
        ->where('YEAR(tanggal)',$tahun)
        ->first();

    $tgkBulan = $tgk['total'] ?? 0;

    $totalBayarSPP += $sppBulan;
    $totalBayarTunggakan += $tunggakanBayar;
    $totalBayarInden += $indenBulan;
    $totalTunggakan += $tgkBulan;

    $s['bulan'][$key] = [
        'label' => $label,
        'spp' => $sppBulan,
        'bayar_tunggakan' => $tunggakanBayar,
        'inden' => $indenBulan,
        'tunggakan' => $tgkBulan,
        'ada' => $tgkBulan !== 0
    ];
}

        $s['total_bayar_spp'] = $totalBayarSPP;
        $s['total_bayar_tunggakan'] = $totalBayarTunggakan;
        $s['total_bayar_inden'] = $totalBayarInden;
        $s['total_tunggakan'] = $totalTunggakan;

        $totalBayar = $totalBayarSPP + $totalBayarTunggakan + $totalBayarInden;

        $s['sisa_tunggakan'] = $totalTunggakan - $totalBayar;

    }

    return view('laporan/spp',[
        'santri'=>$santri,
        'bulan'=>$bulan
    ]);

}


public function download()
{

    $detail    = new DetailModel();
    $tunggakan = new TunggakanModel();
    $santri    = new SantriModel();

    $bulan = [
        '2025-06' => 'Juni 2025',
        '2025-07' => 'Juli 2025',
        '2025-08' => 'Agustus 2025',
        '2025-09' => 'September 2025',
        '2025-10' => 'Oktober 2025',
        '2025-11' => 'November 2025',
        '2025-12' => 'Desember 2025',
        '2026-01' => 'Januari 2026',
        '2026-02' => 'Februari 2026',
        '2026-03' => 'Maret 2026',
        '2026-04' => 'April 2026',
        '2026-05' => 'Mei 2026',
        '2026-06' => 'Juni 2026',
        '2026-07' => 'Juli 2026',
    ];

    $dataSantri = $santri
        ->select("
            nisn,
            nama,
            program,
            spp,
            CASE 
                WHEN kelas IS NULL OR kelas='' THEN 'lulus'
                ELSE kelas
            END AS kelas
        ")
        ->findAll();

    $filename = 'laporan_spp_santri_'.date('Ymd_His').'.csv';

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $fp = fopen('php://output','w');

    $header = ['NISN','Nama','Program','Kelas','SPP'];

    foreach($bulan as $b){

        $header[]="Bayar Tunggakan $b";
        $header[]="Bayar SPP $b";
        $header[]="Bayar Inden $b";
        $header[]="Tunggakan $b";

    }

    $header[]='Total Bayar Tunggakan';
    $header[]='Total Bayar SPP';
    $header[]='Total Bayar Inden';
    $header[]='Total Tunggakan';
    $header[]='Sisa Tunggakan';

    fputcsv($fp,$header);

    foreach($dataSantri as $s){

        $row=[$s['nisn'],$s['nama'],$s['program'],$s['kelas'],$s['spp']];

        $totalBayarSPP=0;
        $totalBayarTunggakan=0;
        $totalBayarInden=0;
        $totalTunggakan=0;

        foreach($bulan as $key=>$label){

            [$tahun,$bln]=explode('-',$key);

            if ($key == '2025-06') {

    $sppBulan = 0;
    $tunggakanBayar = 0;
    $indenBulan = 0;

} else {

    $bayar=$detail
        ->select("
            SUM(COALESCE(detail.spp,0)) AS spp,
            SUM(COALESCE(detail.tunggakan_spp,0)) AS tunggakan,
            SUM(COALESCE(detail.inden_spp,0)) AS inden
        ")
        ->join('transfer','transfer.idtrans=detail.id')
        ->where('transfer.nisn',$s['nisn'])
        ->where('MONTH(detail.tanggal)',$bln)
        ->where('YEAR(detail.tanggal)',$tahun)
        ->first();

    $sppBulan=$bayar['spp'] ?? 0;
    $tunggakanBayar=$bayar['tunggakan'] ?? 0;
    $indenBulan=$bayar['inden'] ?? 0;

}
            $tgk=$tunggakan
                ->select('SUM(nominal) AS total')
                ->where('nisn',$s['nisn'])
                ->where('MONTH(tanggal)',$bln)
                ->where('YEAR(tanggal)',$tahun)
                ->first();

            $tgkBulan=$tgk['total'] ?? 0;

            $row[]=$tunggakanBayar;
            $row[]=$sppBulan;
            $row[]=$indenBulan;
            $row[]=$tgkBulan;

            $totalBayarSPP+=$sppBulan;
            $totalBayarTunggakan+=$tunggakanBayar;
            $totalBayarInden+=$indenBulan;
            $totalTunggakan+=$tgkBulan;

        }

        $row[]=$totalBayarTunggakan;
        $row[]=$totalBayarSPP;
        $row[]=$totalBayarInden;
        $row[]=$totalTunggakan;

        $totalBayar=$totalBayarTunggakan+$totalBayarSPP+$totalBayarInden;

        $row[]=$totalTunggakan-$totalBayar;

        fputcsv($fp,$row);

    }

    fclose($fp);
    exit;

}

public function downloaddaftarulang()
{

    $detail = new DetailModel();
    $santri = new SantriModel();

    $bulan = [
        '2025-06' => 'Juni 2025',
        '2025-07' => 'Juli 2025',
        '2025-08' => 'Agustus 2025',
        '2025-09' => 'September 2025',
        '2025-10' => 'Oktober 2025',
        '2025-11' => 'November 2025',
        '2025-12' => 'Desember 2025',
        '2026-01' => 'Januari 2026',
        '2026-02' => 'Februari 2026',
        '2026-03' => 'Maret 2026',
        '2026-04' => 'April 2026',
        '2026-05' => 'Mei 2026',
        '2026-06' => 'Juni 2026',
        '2026-07' => 'Juli 2026',
    ];

    $dataSantri = $santri
        ->select("
            nisn,
            nama,
            program,
            tunggakandu,
            tunggakandu2,
            tunggakandu3,
            CASE 
                WHEN kelas IS NULL OR kelas='' THEN 'lulus'
                ELSE kelas
            END AS kelas
        ")
        ->findAll();

    $filename = 'laporan_daftar_ulang_'.date('Ymd_His').'.csv';

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $fp = fopen('php://output','w');

    $header = ['NISN','Nama','Program','Kelas', 'tunggakan du kelas 1', 'tunggakan du kelas 2', 'tunggakan du kelas 3'];

    foreach($bulan as $b){
        $header[]="Bayar Daftar Ulang $b";
    }

    $header[]='Total Bayar Daftar Ulang';

    fputcsv($fp,$header);

    foreach($dataSantri as $s){

        $row = [$s['nisn'],$s['nama'],$s['program'],$s['kelas'],$s['tunggakandu'], $s['tunggakandu2'],$s['tunggakandu3']];

        $totalDaftarUlang = 0;

        foreach($bulan as $key=>$label){

            [$tahun,$bln] = explode('-',$key);

            $bayar = $detail
                ->select("SUM(COALESCE(detail.daftarulang,0)) AS daftarulang")
                ->join('transfer','transfer.idtrans=detail.id')
                ->where('transfer.nisn',$s['nisn'])
                ->where('MONTH(detail.tanggal)',$bln)
                ->where('YEAR(detail.tanggal)',$tahun)
                ->first();

            $daftarUlangBulan = $bayar['daftarulang'] ?? 0;

            $row[] = $daftarUlangBulan;

            $totalDaftarUlang += $daftarUlangBulan;
        }

        $row[] = $totalDaftarUlang;

        fputcsv($fp,$row);
    }

    fclose($fp);
    exit;

}

public function downloaddupsb()
{

    $detail = new DetailModel();
    $santri = new PsbModel();

    $bulan = [
        '2025-06' => 'Juni 2025',
        '2025-07' => 'Juli 2025',
        '2025-08' => 'Agustus 2025',
        '2025-09' => 'September 2025',
        '2025-10' => 'Oktober 2025',
        '2025-11' => 'November 2025',
        '2025-12' => 'Desember 2025',
        '2026-01' => 'Januari 2026',
        '2026-02' => 'Februari 2026',
        '2026-03' => 'Maret 2026',
        '2026-04' => 'April 2026',
        '2026-05' => 'Mei 2026',
        '2026-06' => 'Juni 2026',
        '2026-07' => 'Juli 2026',
    ];

    $dataSantri = $santri
        ->select("
            nisn,
            nama,
            status,
            daftarulang,
            tunggakandu,
            jenjang
        ")
        ->where("status", "diterima")
        ->findAll();

    $filename = 'laporan_psb_'.date('Ymd_His').'.csv';

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $fp = fopen('php://output','w');

    $header = ['NISN','Nama','Jenjang','Status', 'Daftar Ulang', 'Tunggakan du'];

    foreach($bulan as $b){
        $header[]="Bayar Daftar Ulang $b";
    }

    $header[]='Total Bayar Daftar Ulang';

    fputcsv($fp,$header);

    foreach($dataSantri as $s){

        $row = [$s['nisn'],$s['nama'],$s['jenjang'],$s['status'],$s['daftarulang'], $s['tunggakandu']];

        $totalDaftarUlang = 0;

        foreach($bulan as $key=>$label){

            [$tahun,$bln] = explode('-',$key);

            $bayar = $detail
                ->select("SUM(COALESCE(detail.daftarulang,0)) AS daftarulang")
                ->join('transfer','transfer.idtrans=detail.id')
                ->where('transfer.nisn',$s['nisn'])
                ->where('MONTH(detail.tanggal)',$bln)
                ->where('YEAR(detail.tanggal)',$tahun)
                ->first();

            $daftarUlangBulan = $bayar['daftarulang'] ?? 0;

            $row[] = $daftarUlangBulan;

            $totalDaftarUlang += $daftarUlangBulan;
        }

        $row[] = $totalDaftarUlang;

        fputcsv($fp,$row);
    }

    fclose($fp);
    exit;

}

public function downloaddualumni()
{

    $detail = new DetailModel();
    $santri = new AlumniModel();

    $bulan = [
        '2025-06' => 'Juni 2025',
        '2025-07' => 'Juli 2025',
        '2025-08' => 'Agustus 2025',
        '2025-09' => 'September 2025',
        '2025-10' => 'Oktober 2025',
        '2025-11' => 'November 2025',
        '2025-12' => 'Desember 2025',
        '2026-01' => 'Januari 2026',
        '2026-02' => 'Februari 2026',
        '2026-03' => 'Maret 2026',
        '2026-04' => 'April 2026',
        '2026-05' => 'Mei 2026',
        '2026-06' => 'Juni 2026',
        '2026-07' => 'Juli 2026',
    ];

    $dataSantri = $santri
        ->select("
            nisn,
            nama,
            kelas,
            program,
            tunggakandu,
            tunggakantl
        ")
        ->findAll();

    $filename = 'laporan_du_alumni_'.date('Ymd_His').'.csv';

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $fp = fopen('php://output','w');

    $header = ['NISN','Nama','Program','Kelas', 'tunggakan du', 'tunggakan tl'];

    foreach($bulan as $b){
        $header[]="Bayar Daftar Ulang $b";
    }

    $header[]='Total Bayar Daftar Ulang';

    fputcsv($fp,$header);

    foreach($dataSantri as $s){

        $row = [$s['nisn'],$s['nama'],$s['program'],$s['kelas'],$s['tunggakandu'], $s['tunggakantl']];

        $totalDaftarUlang = 0;

        foreach($bulan as $key=>$label){

            [$tahun,$bln] = explode('-',$key);

            $bayar = $detail
                ->select("SUM(COALESCE(detail.daftarulang,0)) AS daftarulang")
                ->join('transfer','transfer.idtrans=detail.id')
                ->where('transfer.nisn',$s['nisn'])
                ->where('MONTH(detail.tanggal)',$bln)
                ->where('YEAR(detail.tanggal)',$tahun)
                ->first();

            $daftarUlangBulan = $bayar['daftarulang'] ?? 0;

            $row[] = $daftarUlangBulan;

            $totalDaftarUlang += $daftarUlangBulan;
        }

        $row[] = $totalDaftarUlang;

        fputcsv($fp,$row);
    }

    fclose($fp);
    exit;

}

}