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
        transfer.nama,
        transfer.kelas,
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
            'Nama','Kelas','Tanggal','Rekening','Program','Saldo Masuk','Keterangan',
            'Daftar Ulang','Tunggakan SPP','Inden SPP','SPP','Uang Saku','Infaq','Formulir'
        ]);

        foreach ($rows as $r) {
            fputcsv($out, [
                $r['nama'],
                $r['kelas'],
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
    
public function updateDetail($idtrans)
{
    try {
        $db = db_connect();
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'JSON tidak terbaca'
            ]);
        }

        // Update tabel detail
        $db->table('detail')
            ->where('id', $idtrans)
            ->update([
                'spp'           => (int)($data['spp'] ?? 0),
                'tunggakan_spp' => (int)($data['tunggakan_spp'] ?? 0),
                'inden_spp'     => (int)($data['inden_spp'] ?? 0),
                'daftarulang'   => (int)($data['daftarulang'] ?? 0),
                'uangsaku'      => (int)($data['uangsaku'] ?? 0),
                'infaq'         => (int)($data['infaq'] ?? 0),
                'formulir'      => (int)($data['formulir'] ?? 0),
            ]);

        // Hitung total
        $total =
            (int)($data['spp'] ?? 0) +
            (int)($data['tunggakan_spp'] ?? 0) +
            (int)($data['inden_spp'] ?? 0) +
            (int)($data['daftarulang'] ?? 0) +
            (int)($data['uangsaku'] ?? 0) +
            (int)($data['infaq'] ?? 0) +
            (int)($data['formulir'] ?? 0);

        // Update header
        $db->table('transfer')
            ->where('idtrans', $idtrans)
            ->update([
                'saldomasuk' => $total
            ]);

        return $this->response->setJSON([
            'status' => 'ok',
            'total' => $total
        ]);

    } catch (\Throwable $e) {
        return $this->response->setStatusCode(500)
            ->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
    }
}

    public function delete($id)
        {
            db_connect()
                ->table('transfer')
                ->where('idtrans', $id)
                ->delete();
        
            return $this->response->setJSON([
                'status' => 'deleted'
            ]);
        }

}
