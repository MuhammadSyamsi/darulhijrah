<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        $group = new GroupModel();

        // Cek apakah grup sudah ada
        if ($group->where('name', 'superadmin')->first() === null) {
            $group->insert([
                'name'        => 'superadmin',
                'description' => 'Full access to all modules',
            ]);
            echo "Grup 'superadmin' berhasil dibuat.\n";
        }

        // Cari user 'syamsi'
        $user = (new UserModel())->where('username', 'syamsi')->first();

        if ($user) {
            $group->addUserToGroup($user['id'], 'superadmin');
            echo "User 'syamsi' berhasil dimasukkan ke grup superadmin.\n";
        } else {
            echo "User 'syamsi' tidak ditemukan.\n";
        }
    }
}
