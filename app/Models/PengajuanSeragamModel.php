<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanSeragamModel extends Model
{
    protected $table      = 'pengajuan_seragam';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tanggal', 'status', 'catatan', 'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // tidak pakai updated_at
}
