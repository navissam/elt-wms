<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Admlog_model;

class Admlog extends BaseController
{
    protected $admlogModel;
    public function __construct()
    {
        $this->admlogModel = new Admlog_model();
    }

    public function index()
    {
        $data['title'] = '系统日志';
        $data['active']['admlog']['admlog'] = true;
        return view('tyck/admlog/v_admlog_index', $data);
    }


    public function getAll()
    {
        return json_encode($this->admlogModel->getAll());
    }

    public function getByDate($start, $finish)
    {
        return json_encode($this->admlogModel->getByDate($start, $finish));
    }
}
