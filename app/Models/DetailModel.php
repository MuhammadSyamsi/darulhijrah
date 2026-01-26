<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailModel extends Model
{
    protected $table      = 'detail';
    protected $allowedFields = ['id', 'tanggal', 'daftarulang', 'tunggakan', 'spp', 'uangsaku', 'infaq', 'formulir', 'rekening', 'program'];
}
