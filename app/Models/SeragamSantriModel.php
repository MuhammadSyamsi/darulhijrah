<?php

namespace App\Models;

use CodeIgniter\Model;

class SeragamSantriModel extends Model
{
    protected $table      = 'seragam_santri';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nisn', 'jenis_seragam', 'kategori', 'ukuran', 'status',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
}
