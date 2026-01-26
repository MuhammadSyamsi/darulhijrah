<?php

namespace App\Models;

use CodeIgniter\Model;

class KewajibanModel extends Model
{
    protected $table            = 'kewajiban';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nisn',
        'tag',
        'status',
    ];

    protected $useTimestamps = true;

    // ===============================
    // Helper Method
    // ===============================

    public function getBySantri($nisn)
    {
        return $this->where('nisn', $nisn)->findAll();
    }
}
