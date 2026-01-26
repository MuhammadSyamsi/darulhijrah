<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Entities\User;

class UserController extends BaseController
{
    public function create()
    {
        $groupModel = new GroupModel();
        $data['groups'] = $groupModel->findAll();

        return view('admin/user_add', $data);
    }

public function store()
{
    $users = new UserModel();

    // Buat entitas user dengan status aktif langsung
    $userEntity = new User([
        'username' => $this->request->getPost('username'),
        'email'    => $this->request->getPost('email'),
        'password' => $this->request->getPost('password'),
        'active'   => 1, // <- Aktif langsung
    ]);

    if ($users->save($userEntity)) {
        $newUserId = $users->getInsertID();

        if ($newUserId) {
            // Tangkap nama grup dari input
            $groupName = $this->request->getPost('group');

            // Cek grup dan masukkan ke ID grup yang sesuai
            $groupId = null;
            switch ($groupName) {
                case 'musrif':
                    $groupId = 2;
                    break;
                case 'kamad':
                    $groupId = 3;
                    break;
                case 'ustadz':
                    $groupId = 4;
                    break;
                default:
                    $groupId = null;
                    break;
            }

            // Jika grup valid, masukkan ke auth_groups_users
            if ($groupId) {
                $db = \Config\Database::connect();
                $builder = $db->table('auth_groups_users');
                $builder->insert([
                    'user_id'  => $newUserId,
                    'group_id' => $groupId
                ]);
            }
        }

        return redirect()->to('/admin/user/add')->with('message', 'User berhasil ditambahkan.');
    } else {
        return redirect()->back()->withInput()->with('errors', $users->errors());
    }
}
}
