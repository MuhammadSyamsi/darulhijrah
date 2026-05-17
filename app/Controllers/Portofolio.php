<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\TransferModel;
use App\Models\DetailModel;
use App\Models\TunggakanModel;

class Portofolio extends BaseController
{

    public function index($nisn = null)
    {

        $santriModel     = new SantriModel();
        $transferModel   = new TransferModel();
        $detailModel     = new DetailModel();
        $tunggakanModel  = new TunggakanModel();

        $santri = null;

        // ======================
        // AMBIL DATA SANTRI
        // ======================

        if ($nisn) {
            $santri = $santriModel
                ->where('nisn', $nisn)
                ->first();
        }

        if (!$santri) {
            return view('pages/portofolio', [
                'santri' => null,
                'transfer' => [],
                'detail' => [],
                'tunggakan' => []
            ]);
        }

        $nisn = $santri['nisn'];

        // ======================
        // RIWAYAT TRANSFER
        // ======================

        $transfer = $transferModel
            ->where('nisn', $nisn)
            ->orderBy('tanggal', 'ASC')
            ->findAll();


        // ======================
        // DETAIL PEMBAYARAN
        // ======================

        $detail = $detailModel
            ->select('
                SUM(spp) as spp,
                SUM(inden_spp) as inden_spp,
                SUM(daftarulang) as daftarulang,
                SUM(uangsaku) as uangsaku,
                SUM(infaq) as infaq,
                SUM(formulir) as formulir
            ')
            ->join('transfer', 'transfer.idtrans = detail.id', 'left')
            ->where('transfer.nisn', $nisn)
            ->first();


        // ======================
        // TUNGGAKAN
        // ======================

        $tunggakan = $tunggakanModel
            ->where('nisn', $nisn)
            ->orderBy('tanggal', 'ASC')
            ->first();


        return view('pages/portofolio', [
            'santri'    => $santri,
            'transfer'  => $transfer,
            'detail'    => $detail,
            'tunggakan' => $tunggakan
        ]);
    }


    // ======================
    // API AUTOCOMPLETE SANTRI
    // ======================

    public function cariSantri()
    {

        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return $this->response->setJSON([]);
        }

        $santriModel = new SantriModel();

        $data = $santriModel
            ->select('nisn,nama')
            ->like('nama', $keyword)
            ->orderBy('nama', 'ASC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON($data);
    }
}