<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Goods_model;

class Goods extends BaseController
{
    protected $goodsModel;
    public function __construct()
    {
        $this->goodsModel = new Goods_model();
    }

    public function index()
    {
        $data['title'] = '物料管理';
        $data['active']['basic']['goods'] = true;
        return view('tyck/goods/v_goods_index', $data);
    }

    public function getAll()
    {
        return json_encode($this->goodsModel->getAll());
    }

    public function update()
    {
        $id = $this->request->getPost('goods_id');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/goods');
        }
        $validation =  \Config\Services::validation();
        $valid = [
            'name_type' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'unit' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'safety' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
        ];
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $this->goodsModel->update($this->request->getPost('goods_id'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'goods',
                'method' => 'update',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => json_encode($id),
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'goods',
            'method' => 'update',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
