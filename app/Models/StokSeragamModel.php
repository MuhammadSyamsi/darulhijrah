<?php

namespace App\Models;

use CodeIgniter\Model;

class StokSeragamModel extends Model
{
    protected $table      = 'stok_seragam';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenis_seragam', 'kategori', 'ukuran', 'jumlah', 'updated_at'
    ];

    protected $useTimestamps = false;
}
