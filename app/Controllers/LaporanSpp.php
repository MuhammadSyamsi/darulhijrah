<?php

namespace App\Controllers;

use App\Models\TransferModel;
use App\Models\DetailModel;
use App\Models\TunggakanModel;
use App\Models\SantriModel;

class LaporanSpp extends BaseController
{
    public function index()
    {
        $transfer  = new TransferModel();
        $detail    = new DetailModel();
        $tunggakan = new TunggakanModel();

        $bulan = [
            '2025-07' => 'Juli 2025',
            '2025-08' => 'Agustus 2025',
            '2025-09' => 'September 2025',
            '2025-10' => 'Oktober 2025',
            '2025-11' => 'November 2025',
            '2025-12' => 'Desember 2025',
            '2026-01' => 'Januari 2026',
            '2026-02' => 'Februari 2026',
        ];

       $santriModel = new SantriModel();

$santri = $santriModel
    ->select("
        nisn,
        nama,
        kelas
    ")
    ->findAll();

        foreach ($santri as &$s) {

            $totalBayar     = 0;
            $totalTunggakan = 0;

            foreach ($bulan as $key => $label) {
                [$tahun, $bln] = explode('-', $key);

                // ===== BAYAR (JOIN transfer → detail) =====
                $bayar = $detail
                    ->select('
                        SUM(
                            COALESCE(detail.spp,0) +
                            COALESCE(detail.tunggakan_spp,0) +
                            COALESCE(detail.inden_spp,0)
                        ) AS total
                    ')
                    ->join('transfer', 'transfer.idtrans = detail.id')
                    ->where('transfer.nisn', $s['nisn'])
                    ->where('MONTH(detail.tanggal)', $bln)
                    ->where('YEAR(detail.tanggal)', $tahun)
                    ->first();

                // ===== TUNGGAKAN =====
                $tgk = $tunggakan
                    ->select('SUM(nominal) AS total')
                    ->where('nisn', $s['nisn'])
                    ->where('MONTH(tanggal)', $bln)
                    ->where('YEAR(tanggal)', $tahun)
                    ->first();

                $bayarBulan = $bayar['total'] ?? 0;
                $tgkBulan   = $tgk['total'] ?? 0;

                $totalBayar     += $bayarBulan;
                $totalTunggakan += $tgkBulan;

                $s['bulan'][$key] = [
                    'label'     => $label,
                    'bayar'     => $bayarBulan,
                    'tunggakan' => $tgkBulan,
                    'ada'       => $tgkBulan > 0
                ];
            }

            // ===== TOTAL =====
            $s['total_bayar']     = $totalBayar;
            $s['total_tunggakan'] = $totalTunggakan;
            $s['sisa_tunggakan']  = $totalTunggakan - $totalBayar;
        }

        return view('laporan/spp', [
            'santri' => $santri,
            'bulan'  => $bulan
        ]);
    }
}