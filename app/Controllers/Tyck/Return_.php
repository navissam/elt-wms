<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Return_model;

class Return_ extends BaseController
{
    protected $ret_model;
    public function __construct()
    {
        $this->ret_model = new Return_model();
    }

    public function index()
    {
        $data['title'] = '退库';
        $data['active']['ret']['return'] = true;
        return view('tyck/return/v_return_index', $data);
    }

    public function getGoods()
    {
        return json_encode($this->ret_model->getGoods());
    }

    public function getLocation()
    {
        return json_encode($this->ret_model->getLocation());
    }

    public function getCompany()
    {
        return json_encode($this->ret_model->getCompany());
    }

    public function getDept($company)
    {
        return json_encode($this->ret_model->getDept($company));
    }

    public function save()
    {
        $id = $this->request->getPost('ret_id');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/return_');
        }

        try {
            $this->ret_model->insert($this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'return',
                'method' => 'save',
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
            'controller' => 'return',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
