<?php

namespace App\Controllers;

use App\Models\TunggakanModel;

class Tunggakan extends BaseController
{
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $model = new TunggakanModel();

        $data = [
            'nisn'       => $this->request->getPost('nisn'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'nominal'    => $this->request->getPost('nominal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if (!$model->insert($data)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $model->errors()
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Tunggakan berhasil ditambahkan'
        ]);
    }
    
    public function create()
{
    $nisn  = $this->request->getGet('nisn');
    $bulan = $this->request->getGet('bulan'); // YYYY-MM

    return view('tunggakan/create', [
        'nisn'    => $nisn,
        'tanggal' => $bulan . '-01'
    ]);
}
}