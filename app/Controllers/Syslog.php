<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Syslog_model;

class Syslog extends BaseController
{
    protected $syslogModel;
    public function __construct()
    {
        $this->syslogModel = new Syslog_model();
    }

    public function index()
    {
        $data['title'] = '系统日志';
        $data['active']['system']['syslog'] = true;
        return view('syslog/v_syslog_index', $data);
    }


    public function getAll()
    {
        return json_encode($this->syslogModel->getAll());
    }

    public function getByDate($start, $finish)
    {
        return json_encode($this->syslogModel->getByDate($start, $finish));
    }
}
