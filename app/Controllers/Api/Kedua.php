<?php

namespace App\Controllers\Api;

use App\Models\TransferModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Kedua extends ResourceController
{
    use ResponseTrait;

    public function show($id = null)
    {
        $model = new TransferModel();

        // Ambil 3 transaksi terakhir dalam satu query saja
        $transaksi = $model
            ->where("nisn", $id)
            ->orderBy("tanggal", "desc")
            ->limit(3)
            ->findAll();

        // Format hasil
        $data = [];
        for ($i = 0; $i < 3; $i++) {
            $row = $transaksi[$i] ?? [
                "tanggal" => null,
                "keterangan" => null,
                "rekening" => null,
                "saldomasuk" => null,
            ];
            $data[] = [
                "tanggal" => $row["tanggal"],
                "keterangan" => $row["keterangan"],
                "rekening" => $row["rekening"],
                "nominal" => $row["saldomasuk"],
            ];
        }

        return $this->response->setJSON($data);
    }
}
