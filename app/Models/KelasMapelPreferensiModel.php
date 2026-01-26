<?php

namespace App\Models;
use CodeIgniter\Model;

class KelasMapelPreferensiModel extends Model
{
    protected $table      = 'kelas_mapel_preferensi';
    protected $primaryKey = 'id';

    protected $allowedFields = ['kelas_id', 'mapel_id'];
}
