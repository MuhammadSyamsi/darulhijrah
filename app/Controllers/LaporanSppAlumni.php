<?php

namespace App\Controllers;

use App\Models\TransferModel;
use App\Models\DetailModel;
use App\Models\TunggakanModel;

class LaporanSppAlumni extends BaseController
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

        /**
         * Ambil alumni dari transfer
         * (nama & kelas terakhir per NISN)
         */
        $alumni = $transfer
            ->select('nisn, nama, kelas')
            ->where('kelas', 'lulus')
            ->groupby('nisn')
            ->findAll();

        foreach ($alumni as &$a) {

            $totalBayar     = 0;
            $totalTunggakan = 0;

            foreach ($bulan as $key => $label) {
                [$tahun, $bln] = explode('-', $key);

                // BAYAR
                $bayar = $detail
                    ->select('
                        SUM(
                            COALESCE(detail.spp,0) +
                            COALESCE(detail.tunggakan_spp,0) +
                            COALESCE(detail.inden_spp,0)
                        ) AS total
                    ')
                    ->join('transfer', 'transfer.idtrans = detail.id')
                    ->where('transfer.nisn', $a['nisn'])
                    ->where('MONTH(detail.tanggal)', $bln)
                    ->where('YEAR(detail.tanggal)', $tahun)
                    ->first();

                // TUNGGAKAN
                $tgk = $tunggakan
                    ->select('SUM(nominal) AS total')
                    ->where('nisn', $a['nisn'])
                    ->where('MONTH(tanggal)', $bln)
                    ->where('YEAR(tanggal)', $tahun)
                    ->first();

                $bayarBulan = $bayar['total'] ?? 0;
                $tgkBulan   = $tgk['total'] ?? 0;

                $totalBayar     += $bayarBulan;
                $totalTunggakan += $tgkBulan;

                $a['bulan'][$key] = [
                    'label'     => $label,
                    'bayar'     => $bayarBulan,
                    'tunggakan' => $tgkBulan,
                    'ada'       => $tgkBulan > 0
                ];
            }

            $a['total_bayar']     = $totalBayar;
            $a['total_tunggakan'] = $totalTunggakan;
            $a['sisa_tunggakan']  = $totalTunggakan - $totalBayar;
        }

        return view('laporan/spp_alumni', [
            'alumni' => $alumni,
            'bulan'  => $bulan
        ]);
    }

    public function download()
    {
        // nanti: Excel / PDF
        return redirect()->back()->with('info', 'Fitur download alumni belum dibuat');
    }
}