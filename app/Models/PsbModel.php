<?php

namespace App\Models;

use CodeIgniter\Model;

class PsbModel extends Model
{
    protected $table      = 'psb';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nisn', 'nama', 'jenjang', 'program', 'tunggakandu', 'daftarulang', 'spp', 'status', 'tanggal', 'formulir', 'rekening', 'kelas', 'tanggallahir', 'asalsekolah', 'tahunmasuk', 'ayah', 'pekerjaanayah', 'alamatayah', 'ibu', 'pekerjaanibu', 'alamatibu', 'kontak1', 'kontak2', 'berkas', 'tempatlahir'];
    public function search($keyword)
    {
        return $this->table('psb')->like('nama', $keyword);
    }
}
