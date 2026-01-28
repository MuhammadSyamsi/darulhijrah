<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStatusKewajiban extends Migration
{
    public function up()
    {
        $this->db->query("
            ALTER TABLE kewajiban
            MODIFY status ENUM(
                'tunggakan',
                'angsur',
                'lunas',
                'terbayar'
            ) NOT NULL DEFAULT 'tunggakan'
        ");
    }

    public function down()
    {
        // rollback ke enum lama (opsional, tapi rapi)
        $this->db->query("
            ALTER TABLE kewajiban
            MODIFY status ENUM(
                'tunggakan',
                'lunas'
            ) NOT NULL DEFAULT 'tunggakan'
        ");
    }
}
