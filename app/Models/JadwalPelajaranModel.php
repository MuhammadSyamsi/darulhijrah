<?php

namespace App\Models;
use CodeIgniter\Model;

class JadwalPelajaranModel extends Model
{
    protected $table      = 'jadwal_pelajaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['slot_id', 'guru_id', 'mapel_id'];

    // Method untuk ambil jadwal berdasarkan hari
    public function getJadwalByHari($hari_id)
    {
        return $this->select('jadwal_pelajaran.id, kelas.nama_kelas as nama_kelas, 
                              mapel.nama_mapel as nama_mapel, guru.nama as nama_guru, 
                              slot_pelajaran.jam_ke, hari.nama_hari as nama_hari')
            ->join('slot_pelajaran', 'jadwal_pelajaran.slot_id = slot_pelajaran.id')
            ->join('hari', 'slot_pelajaran.hari_id = hari.id')
            ->join('kelas', 'slot_pelajaran.kelas_id = kelas.id')
            ->join('guru', 'jadwal_pelajaran.guru_id = guru.id')
            ->join('mapel', 'jadwal_pelajaran.mapel_id = mapel.id')
            ->where('hari.id', $hari_id)
            ->orderBy('jam_ke', 'asc')
            ->findAll();
    }
}
