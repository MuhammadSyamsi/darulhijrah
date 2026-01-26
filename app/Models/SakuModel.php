<?php

namespace App\Models;

use CodeIgniter\Model;

class SakuModel extends Model
{
    protected $table = 'saku';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nisn', 'nama', 'jenjang',
        'saldo_1', 'saldo_2', 'saldo_3', 'saldo_4', 'saldo_5',
        'saldo_6', 'saldo_7', 'saldo_8', 'saldo_9', 'saldo_10',
        'debit_1', 'debit_2', 'debit_3', 'debit_4', 'debit_5',
        'debit_6', 'debit_7', 'debit_8', 'debit_9', 'debit_10',
        'debit_11', 'debit_12', 'debit_13', 'debit_14', 'debit_15',
        'ket_1', 'ket_2', 'ket_3', 'ket_4', 'ket_5',
        'ket_6', 'ket_7', 'ket_8', 'ket_9', 'ket_10',
        'ket_11', 'ket_12', 'ket_13', 'ket_14', 'ket_15',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true; // untuk created_at dan updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = 'array';
}
