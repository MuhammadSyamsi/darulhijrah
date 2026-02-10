<?php

namespace App\Controllers;

use App\Models\TransferModel;
use CodeIgniner\Controller;

class Transfer extends BaseController
{
    protected $transferModel;

    public function __construct()
    {
        $this->transferModel = new TransferModel();
    }

    // halaman utama
    public function index()
    {
        return view('transfer/index');
    }

    // API JSON
    public function data()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $rekening = $this->request->getGet('rekening');
        $program  = $this->request->getGet('program');

        $builder = $this->transferModel
            ->select('
                transfer.idtrans,
                transfer.tanggal,
                transfer.rekening,
                transfer.program,
                transfer.saldomasuk,
                transfer.keterangan,
                detail.daftarulang,
                detail.tunggakan_spp,
                detail.inden_spp,
                detail.spp,
                detail.uangsaku,
                detail.infaq,
                detail.formulir
            ')
            ->join('detail', 'detail.id = transfer.idtrans', 'left');

        if ($rekening) {
            $builder->like('transfer.rekening', $rekening);
        }

        if ($program) {
            $builder->like('transfer.program', $program);
        }

        if ($bulan) {
            $builder->where('MONTH(transfer.tanggal)', $bulan);
        }

        if ($tahun) {
            $builder->where('YEAR(transfer.tanggal)', $tahun);
        }

        $data = $builder->orderBy('transfer.tanggal', 'DESC')->findAll();

        return $this->response->setJSON($data);
    }

    // Download CSV
    public function csv()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $rekening = $this->request->getGet('rekening');
        $program  = $this->request->getGet('program');

        $builder = $this->transferModel
        ->select('
        transfer.tanggal,
        transfer.rekening,
        transfer.program,
        transfer.saldomasuk,
        transfer.keterangan,
        detail.daftarulang,
        detail.tunggakan_spp,
        detail.inden_spp,
        detail.spp,
        detail.uangsaku,
        detail.infaq,
        detail.formulir
        ')
        ->join('detail', 'detail.id = transfer.idtrans', 'left');
        
        if ($rekening) {
            $builder->like('transfer.rekening', $rekening);
        }
        
        if ($program) {
            $builder->like('transfer.program', $program);
        }
        
        if ($bulan) {
            $builder->where('MONTH(transfer.tanggal)', $bulan);
            }
        
        if ($tahun) {
            $builder->where('YEAR(transfer.tanggal)', $tahun);
            }
                
        $rows = $builder->findAll();
                
        $filename = "transfer_{$bulan}_{$tahun}.csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');

        fputcsv($out, [
            'Tanggal','Rekening','Program','Saldo Masuk','Keterangan',
            'Daftar Ulang','Tunggakan SPP','Inden SPP','SPP','Uang Saku','Infaq','Formulir'
        ]);

        foreach ($rows as $r) {
            fputcsv($out, [
                $r['tanggal'],
                $r['rekening'],
                $r['program'],
                $r['saldomasuk'],
                $r['keterangan'],
                $r['daftarulang'],
                $r['tunggakan_spp'],
                $r['inden_spp'],
                $r['spp'],
                $r['uangsaku'],
                $r['infaq'],
                $r['formulir']
            ]);
        }

        fclose($out);
        exit;
    }
}
