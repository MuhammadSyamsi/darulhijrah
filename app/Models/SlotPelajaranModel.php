<?php

namespace App\Models;
use CodeIgniter\Model;

class SlotPelajaranModel extends Model
{
    protected $table      = 'slot_pelajaran';
    protected $primaryKey = 'id';

    protected $allowedFields = ['kelas_id', 'hari_id', 'jam_ke'];
}
