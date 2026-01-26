<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table      = 'guru';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nama', 'nip', 'mapel', 'jabatan', 'kelas', 'pendidikan_akhir'];

    public function search($keyword)
    {
        return $this->table('guru')->like('nama', $keyword);
    }
}

