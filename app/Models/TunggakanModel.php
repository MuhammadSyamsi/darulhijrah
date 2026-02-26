<?php

namespace App\Models;

use CodeIgniter\Model;

class TunggakanModel extends Model
{
    protected $table = 'tunggakan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nisn',
        'tanggal',
        'nominal',
        'keterangan'
    ];
    protected $useTimestamps = true;
}