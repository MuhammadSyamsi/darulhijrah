<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPengajuanSeragamModel extends Model
{
    protected $table      = 'detail_pengajuan_seragam';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pengajuan_id', 'jenis_seragam', 'kategori', 'ukuran', 'jumlah'
    ];

    protected $useTimestamps = false;
}
