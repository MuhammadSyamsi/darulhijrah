<?php

namespace App\Models;

use CodeIgniter\Model;

class SantriModel extends Model
{
    protected $table      = 'santri';
    protected $primaryKey = 'nisn';
    protected $allowedFields = ['nisn', 'nama', 'tunggakanspp', 'tunggakandu',  'tunggakandu2', 'tunggakandu3', 'tunggakantl', 'du', 'spp', 'kelas', 'tahunmasuk', 'saku', 'hp', 'program', 'jenjang', 'tempatlahir', 'tanggallahir', 'asalsekolah', 'ayah', 'pekerjaanayah', 'alamatayah', 'ibu', 'pekerjaanibu', 'alamatibu', 'kontak1', 'kontak2', 'berkas'];

    public function cari($keyword)
    {
        return $this->table('santri')->like('nama', $keyword)->findAll(5);
    } 
}

