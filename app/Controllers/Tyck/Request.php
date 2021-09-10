<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Request_model;

class Request extends BaseController
{
    protected $req_model;
    public function __construct()
    {
        $this->req_model = new Request_model();
    }

    public function index()
    {
        $data['title'] = '领用申请';
        $data['active']['request']['request'] = true;
        return view('tyck/request/v_request_index', $data);
    }

    public function getCompany()
    {
        return json_encode($this->req_model->getCompany());
    }

    public function getDept($company)
    {
        return json_encode($this->req_model->getDept($company));
    }

    public function getInv()
    {
        return json_encode($this->req_model->getInv());
    }

    public function save()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/request');
        }

        $items = json_decode($this->request->getPost('items'));

        if (count($items) == 0) {
            return json_encode([
                'status' => 'invalid',
                'errors' => ['items' => '没有物料数据']
            ]);
        }
        try {
            $this->req_model->truncate();
            $data = [];
            foreach ($items as $item) {
                $a = [
                    'inv_id' => $item->inv_id,
                    'company' => $item->company,
                    'goods_id' => $item->goods_id,
                    'name_type' => $item->name_type,
                    'unit' => $item->unit,
                    'qty' => $item->qty,
                    'location' => $item->location,
                    'useFor' => $item->useFor,
                    'remark' => $item->remark,
                ];
                array_push($data, $a);
            }
            $res = $this->req_model->request($data);
            // $this->role_->delete('a');
        } catch (\Exception $e) {

            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }

        return json_encode([
            'status' => 'success',
        ]);
    }

    public function req_print_array()
    {
        $company = $this->request->getPost('req_company');
        $d = $this->request->getPost('req_dept');
        $dep = $this->req_model->req_dept($d);
        if ($d == null || $d == '' || $d == 0) {
            $dept = null;
        } elseif ($dep == null) {
            $dept = $d;
        } else {
            $dept = $dep[0]['deptName'];
        }
        $data['header'] = [
            'req_company' => $this->request->getPost('req_company'),
            // 'req_dept' => $dept[0]['deptName'],
            // 'req_name' => $this->request->getPost('req_name'),
            'req_dept' => $dept,
            'req_name' => ($company == null || $company == '') ? null : $this->request->getPost('req_name'),
            'req_date' => $this->request->getPost('req_date'),
        ];
        $data['cmp'] = $this->req_model->req_company($company);
        $data['rows'] = $this->req_model->req_print_data();
        // dd($data);
        return view('tyck/request/p_req', $data);
    }
}
