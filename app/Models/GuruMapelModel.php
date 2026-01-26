<?php

namespace App\Models;
use CodeIgniter\Model;

class GuruMapelModel extends Model
{
    protected $table      = 'guru_mapel_mingguan';
    protected $primaryKey = 'id';

    protected $allowedFields = ['guru_id', 'mapel_id', 'jumlah_jam'];
}
