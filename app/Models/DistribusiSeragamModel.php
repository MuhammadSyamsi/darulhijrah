<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiSeragamModel extends Model
{
    protected $table      = 'distribusi_seragam';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nisn', 'jenis_seragam', 'kategori', 'ukuran', 'tanggal_distribusi', 'keterangan', 'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // tidak pakai updated_at
}
