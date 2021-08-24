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
        return view('tyck/sti_report/v_sti_basic_report_index', $data);
    }

    public function sti_advanced()
    {
        $data['title'] = '入库报表';
        $data['active']['report']['sti_report'] = true;
        return view('tyck/sti_report/v_sti_filter_index', $data);
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

    public function sti_report_basic($start, $finish)
    {
        return json_encode($this->report_model->sti_report_basic($start, $finish));
    }

    public function sti_report_adv()
    {
        $data['title'] = '入库报表';
        // dd($this->request->getPost());
        $choosen = $this->request->getPost('choosen');
        $start = $this->request->getPost('start');

        $f = $this->request->getPost('finish');
        $date = date_create($f);
        date_modify($date, "+1 days");
        $finish = date_format($date, "Y-m-d");

        $company = $this->request->getPost('s_company');
        $goods = $this->request->getPost('s_goods_id');
        $location = $this->request->getPost('s_location');
        // dd($choosen, $start, $finish, $company, $goods, $location);
        $data['rows'] = $this->report_model->sti_report_adv($choosen, $start, $finish, $company, $goods, $location);
        // $rows = $this->report_model->sti_report($choosen, $start, $finish, $company, $goods, $location);
        $header = [
            "sti_id" => "入库编号",
            "company" => "公司",
            "goods_id" => "物料代码",
            "name_type" => "物料名称与规格型号",
            "unit" => "单位",
            "qty" => "入库量",
            "location" => "库位",
            "remark" => "备注"
        ];
        // dd($header);
        $h = [];
        $i = 0;
        for ($i >= 0; $i < count($choosen); $i++) {
            array_push($h, $header[$choosen[$i]]);
        }

        $data['header'] = $h;
        $data['body'] = $choosen;
        // dd($h, $choosen);
        return view('tyck/sti_report/v_sti_report_index', $data);
    }
}
