<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahKolomDetail extends Migration
{
    public function up()
    {
        $fields = [
            'tunggakan_spp' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
                'after'      => 'tunggakan', // posisi setelah kolom tunggakan
            ],
            'inden_spp' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
                'after'      => 'tunggakan_spp',
            ],
        ];

        $this->forge->addColumn('detail', $fields);
    }

    public function down()
    {
        //
    }
}
