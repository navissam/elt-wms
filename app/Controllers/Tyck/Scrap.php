<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Scrap_model;

class Scrap extends BaseController
{
    protected $scr_model;
    public function __construct()
    {
        $this->scr_model = new Scrap_model();
    }

    public function index()
    {
        $data['title'] = '报废';
        $data['active']['scr']['scrap'] = true;
        return view('tyck/scrap/v_scrap_index', $data);
    }

    // public function getGoods($company, $location)
    // {
    //     return json_encode($this->scr_model->getGoods($company, $location));
    // }

    // public function getLocation($company)
    // {
    //     return json_encode($this->scr_model->getLocation($company));
    // }

    // public function getCompany()
    // {
    //     return json_encode($this->scr_model->getCompany());
    // }

    public function getInv()
    {
        return json_encode($this->scr_model->getInv());
    }

    public function save()
    {
        $id = $this->request->getPost('scr_id');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/scrap');
        }

        try {
            $this->scr_model->insert($this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'scrap',
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
            'controller' => 'scrap',
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
