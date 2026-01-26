<?php

namespace App\Models;
use CodeIgniter\Model;

class ValidasiKehadiranModel extends Model
{
    protected $table      = 'validasi_kehadiran';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jadwal_id', 'musrif_id', 'tanggal', 'jam_ke',
        'status_hadir', 'kerapian', 'seragam_sesuai',
        'catatan', 'validasi_at'
    ];
}
