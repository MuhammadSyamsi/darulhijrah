<?php

namespace App\Controllers\Api;

use App\Models\PsbModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Psb extends ResourceController
{
    use ResponseTrait;

    public function show($id = null)
    {
        $model = new PsbModel();
        $data = $model->where('nisn', $id)->first();

        return $this->respond($data);
    }
}
