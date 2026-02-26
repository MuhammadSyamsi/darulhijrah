<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tunggakan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'nominal' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Index untuk relasi ke santri
        $this->forge->addKey('nisn');

        $this->forge->createTable('tunggakan');
    }

    public function down()
    {
        //
    }
}