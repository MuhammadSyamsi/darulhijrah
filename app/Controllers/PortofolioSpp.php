<?php

namespace App\Controllers;

use App\Models\SantriModel;
use App\Models\DetailModel;
use App\Models\TunggakanModel;

class PortofolioSpp extends BaseController
{
    public function index($nisn)
    {
        $santri    = new SantriModel();
        $detail    = new DetailModel();
        $tunggakan = new TunggakanModel();

        $dataSantri = $santri->where('nisn', $nisn)->first();

        // BATAS MULAI JULI 2025
        $startDate = '2025-07-01';

        // DATA PEMBAYARAN (DETAIL + TRANSFER)
        $rows = $detail
            ->select('
                detail.tanggal,
                transfer.saldomasuk,
                transfer.keterangan,
                detail.daftarulang,
                detail.tunggakan_spp,
                detail.spp,
                detail.inden_spp,
                detail.uangsaku,
                detail.formulir,
                detail.infaq
            ')
            ->join('transfer', 'transfer.idtrans = detail.id')
            ->where('transfer.nisn', $nisn)
            ->where('detail.tanggal >=', $startDate)
            ->orderBy('detail.tanggal', 'ASC')
            ->findAll();

        // DATA TUNGGAKAN SPP (TERPISAH)
        $tunggakanRows = $tunggakan
            ->select('tanggal, nominal')
            ->where('nisn', $nisn)
            ->where('keterangan', 'spp')
            ->where('tanggal >=', $startDate)
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // TOTAL PEMBAYARAN
        $total = [
            'saldomasuk'    => 0,
            'daftarulang'   => 0,
            'tunggakan_spp' => 0,
            'spp'           => 0,
            'inden_spp'     => 0,
            'uangsaku'      => 0,
            'formulir'      => 0,
            'infaq'         => 0,
        ];

        foreach ($rows as $r) {
            foreach ($total as $k => $v) {
                $total[$k] += $r[$k] ?? 0;
            }
        }

        // TOTAL TUNGGAKAN
        $totalTunggakan = array_sum(array_column($tunggakanRows, 'nominal'));

        // SISA TUNGGAKAN
        $sisaTunggakan =
            $totalTunggakan -
            ($total['spp'] + $total['tunggakan_spp'] + $total['inden_spp']);

        return view('portofolio/spp', [
            'santri'        => $dataSantri,
            'rows'          => $rows,
            'tunggakanRows' => $tunggakanRows,
            'total'         => $total,
            'totalTunggakan'=> $totalTunggakan,
            'sisaTunggakan' => $sisaTunggakan
        ]);
    }

    /* ================= DOWNLOAD CSV ================= */

    public function download($nisn)
    {
        $detail    = new DetailModel();
        $tunggakan = new TunggakanModel();

        $startDate = '2025-07-01';

        $rows = $detail
            ->select('
                detail.tanggal,
                transfer.saldomasuk,
                transfer.keterangan,
                detail.daftarulang,
                detail.tunggakan_spp,
                detail.spp,
                detail.inden_spp,
                detail.uangsaku,
                detail.formulir,
                detail.infaq
            ')
            ->join('transfer', 'transfer.idtrans = detail.id')
            ->where('transfer.nisn', $nisn)
            ->where('detail.tanggal >=', $startDate)
            ->orderBy('detail.tanggal', 'ASC')
            ->findAll();

        $tunggakanRows = $tunggakan
            ->select('tanggal, nominal')
            ->where('nisn', $nisn)
            ->where('keterangan', 'spp')
            ->where('tanggal >=', $startDate)
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"portofolio_spp_$nisn.csv\"");

        $fp = fopen('php://output', 'w');

        fputcsv($fp, [
            'Tanggal',
            'Saldo Masuk',
            'Keterangan',
            'Daftar Ulang',
            'Tunggakan SPP',
            'SPP',
            'Inden SPP',
            'Uang Saku',
            'Formulir',
            'Infaq'
        ]);

        foreach ($rows as $r) {
            fputcsv($fp, [
                $r['tanggal'],
                $r['saldomasuk'],
                $r['keterangan'],
                $r['daftarulang'],
                $r['tunggakan_spp'],
                $r['spp'],
                $r['inden_spp'],
                $r['uangsaku'],
                $r['formulir'],
                $r['infaq']
            ]);
        }

        fclose($fp);
        exit;
    }
}