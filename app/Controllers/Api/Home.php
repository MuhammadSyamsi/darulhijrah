<?php

namespace App\Controllers\Api;

use App\Models\SantriModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $keyword = $this->request->getGet("search");
        $model = new SantriModel();

        if (!$keyword) {
            $data = $model->findAll(5);
        } else {
            $data = $model->cari($keyword);
        }

        return $this->response->setJSON($data);
    }
}

?>