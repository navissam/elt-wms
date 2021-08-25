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
    // filter basic
    public function sti()
    {
        $data['title'] = '入库报表';
        $data['active']['report']['sti_report'] = true;
        return view('tyck/sti_report/v_sti_basic_report_index', $data);
    }

    public function sto()
    {
        $data['title'] = '出库报表';
        $data['active']['report']['sto_report'] = true;
        return view('tyck/sto_report/v_sto_basic_report_index', $data);
    }

    public function ret()
    {
        $data['title'] = '退库报表';
        $data['active']['report']['ret_report'] = true;
        return view('tyck/ret_report/v_ret_basic_report_index', $data);
    }

    // filter advanced
    public function sti_advanced()
    {
        $data['title'] = '入库报表';
        $data['active']['report']['sti_report'] = true;
        return view('tyck/sti_report/v_sti_filter_index', $data);
    }

    public function sto_advanced()
    {
        $data['title'] = '出库报表';
        $data['active']['report']['sto_report'] = true;
        return view('tyck/sto_report/v_sto_filter_index', $data);
    }

    public function ret_advanced()
    {
        $data['title'] = '退库报表';
        $data['active']['report']['ret_report'] = true;
        return view('tyck/ret_report/v_ret_filter_index', $data);
    }

    public function getGoods()
    {
        return json_encode($this->report_model->getGoods());
    }

    public function getCompany()
    {
        return json_encode($this->report_model->getCompany());
    }

    public function getLocation()
    {
        return json_encode($this->report_model->getLocation());
    }

    public function getRDept()
    {
        return json_encode($this->report_model->getRDept());
    }

    public function getRName()
    {
        return json_encode($this->report_model->getRName());
    }

    public function getRetDept()
    {
        return json_encode($this->report_model->getRetDept());
    }

    public function getRetName()
    {
        return json_encode($this->report_model->getRetName());
    }

    // report basic
    public function sti_report_basic($start, $finish)
    {
        return json_encode($this->report_model->sti_report_basic($start, $finish));
    }

    public function sto_report_basic($start, $finish)
    {
        return json_encode($this->report_model->sto_report_basic($start, $finish));
    }

    public function ret_report_basic($start, $finish)
    {
        return json_encode($this->report_model->ret_report_basic($start, $finish));
    }

    // report advanced
    public function sti_report_adv()
    {
        $data['title'] = '入库报表';
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
        $header = [
            "sti_id" => "入库编号",
            "company" => "公司",
            "goods_id" => "物料代码",
            "name_type" => "物料名称与规格型号",
            "unit" => "单位",
            "qty" => "入库量",
            "location" => "库位",
            "created_at" => "入库日期",
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
        return view('tyck/sti_report/v_sti_report_index', $data);
    }

    public function sto_report_adv()
    {
        $data['title'] = '出库报表';
        $choosen = $this->request->getPost('choosen');
        $start = $this->request->getPost('start');

        $f = $this->request->getPost('finish');
        $date = date_create($f);
        date_modify($date, "+1 days");
        $finish = date_format($date, "Y-m-d");

        $company = $this->request->getPost('s_company');
        $goods = $this->request->getPost('s_goods_id');
        $location = $this->request->getPost('s_location');
        $r_company = $this->request->getPost('s_r_company');
        $r_dept = $this->request->getPost('s_r_dept');
        $r_name = $this->request->getPost('s_r_name');

        // dd($choosen, $start, $finish, $company, $goods, $location);
        $data['rows'] = $this->report_model->sto_report_adv($choosen, $start, $finish, $company, $goods, $location, $r_company, $r_dept, $r_name);
        $header = [
            "sto_id" => "出库编号",
            "sto_date" => "出库日期",
            "company" => "公司",
            "goods_id" => "物料代码",
            "name_type" => "物料名称与规格型号",
            "unit" => "单位",
            "qty" => "出库量",
            "location" => "库位",
            "recipient_company" => "领用公司",
            "recipient_dept" => "领用部门",
            "recipient_name" => "领用人",
            "remark" => "备注",
            "stock_left" => "出库后数量"
        ];
        $h = [];
        $i = 0;
        for ($i >= 0; $i < count($choosen); $i++) {
            array_push($h, $header[$choosen[$i]]);
        }

        $data['header'] = $h;
        $data['body'] = $choosen;
        // dd($h, $choosen);
        return view('tyck/sto_report/v_sto_report_index', $data);
    }

    public function ret_report_adv()
    {
        $data['title'] = '退库报表';
        $choosen = $this->request->getPost('choosen');
        $start = $this->request->getPost('start');

        $f = $this->request->getPost('finish');
        $date = date_create($f);
        date_modify($date, "+1 days");
        $finish = date_format($date, "Y-m-d");

        $company = $this->request->getPost('s_company');
        $goods = $this->request->getPost('s_goods_id');
        $location = $this->request->getPost('s_location');
        $r_company = $this->request->getPost('s_ret_company');
        $r_dept = $this->request->getPost('s_ret_dept');
        $r_name = $this->request->getPost('s_ret_name');

        // dd($choosen, $start, $finish, $company, $goods, $location);
        $data['rows'] = $this->report_model->ret_report_adv($choosen, $start, $finish, $company, $goods, $location, $r_company, $r_dept, $r_name);
        $header = [
            "ret_id" => "退库编号",
            "ret_date" => "退库日期",
            "company" => "公司",
            "goods_id" => "物料代码",
            "name_type" => "物料名称与规格型号",
            "unit" => "单位",
            "qty" => "退库量",
            "ret_location" => "库位",
            "ret_company" => "领用公司",
            "ret_dept" => "领用部门",
            "ret_name" => "领用人",
            "remark" => "备注",
        ];
        $h = [];
        $i = 0;
        for ($i >= 0; $i < count($choosen); $i++) {
            array_push($h, $header[$choosen[$i]]);
        }

        $data['header'] = $h;
        $data['body'] = $choosen;
        // dd($h, $choosen);
        return view('tyck/ret_report/v_ret_report_index', $data);
    }
}
