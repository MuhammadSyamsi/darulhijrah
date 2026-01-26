<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table      = 'transfer';
    protected $primaryKey = 'idtrans';
    protected $allowedFields = ['idtrans', 'nisn', 'nama', 'kelas', 'saldomasuk', 'tanggal', 'keterangan', 'rekening', 'program'];

    public function search($keyword)
    {
        return $this->table('transfer')->like('nama', $keyword);
    }
}
