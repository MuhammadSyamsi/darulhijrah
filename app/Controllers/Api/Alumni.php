<?php

namespace App\Controllers\Api;

use App\Models\AlumniModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Alumni extends ResourceController
{
    use ResponseTrait;

    public function show($id = null)
    {
        $model = new AlumniModel();
        $data = $model->find($id);

        return $this->respond($data);
    }
}
