<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Safety_model;

class Safety extends BaseController
{
    protected $safetyModel;
    public function __construct()
    {
        $this->safetyModel = new Safety_model();
    }

    public function getAllTyck()
    {
        return json_encode([
            'data' => $this->safetyModel->getAllTyck()
        ]);
    }
}
