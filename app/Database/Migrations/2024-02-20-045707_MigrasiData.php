<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKewajiban extends Migration
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
                'constraint' => 20,
            ],
            'tag' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['lunas', 'tunggakan'],
                'default'    => 'tunggakan',
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

        $this->forge->addKey('id', true);
        $this->forge->addKey('nisn');
        $this->forge->createTable('kewajiban');
    }

    public function down()
    {
        $this->forge->dropTable('kewajiban');
    }
}
