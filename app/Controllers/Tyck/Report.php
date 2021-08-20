<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Report_model;

class Report extends BaseController
{
    protected $report_model;
    public function __construct()
    {
        $this->report_model = new Report_model();
    }

    public function sti()
    {
        $data['title'] = '入库报表';
        $data['active']['report']['sti_report'] = true;
        return view('tyck/report/v_sti_report_index', $data);
    }

    public function getGoods()
    {
        return json_encode($this->report_model->getGoods());
    }

    public function getLocation()
    {
        return json_encode($this->report_model->getLocation());
    }

    public function getCompany()
    {
        return json_encode($this->report_model->getCompany());
    }

    public function getInv()
    {
        return json_encode($this->report_model->getInv());
    }
}
