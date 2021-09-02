<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Stock_out_model;

class Stock_out extends BaseController
{
    protected $sto_model;
    public function __construct()
    {
        $this->sto_model = new Stock_out_model();
    }

    public function index()
    {
        $data['title'] = '出库';
        $data['active']['sto']['stock_out'] = true;
        return view('tyck/stock_out/v_stock_out_index', $data);
    }

    public function sto_print()
    {
        $data['title'] = '单据交接';
        $data['active']['sto_print']['print'] = true;
        return view('tyck/stock_out/v_sto_print_index', $data);
    }

    public function getAll()
    {
        return json_encode($this->sto_model->getAll());
    }

    public function getCompany()
    {
        return json_encode($this->sto_model->getCompany());
    }

    public function getDept($company)
    {
        return json_encode($this->sto_model->getDept($company));
    }

    public function getInv()
    {
        return json_encode($this->sto_model->getInv());
    }

    public function save()
    {
        $id = $this->request->getPost('sto_id');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/stock_out');
        }

        $recipient_company = $this->request->getPost('recipient_company');
        $recipient_dept = $this->request->getPost('recipient_dept');
        $recipient_name = $this->request->getPost('recipient_name');
        $sto_date = $this->request->getPost('sto_date');
        $items = json_decode($this->request->getPost('items'));

        if (count($items) == 0) {
            return json_encode([
                'status' => 'invalid',
                'errors' => ['items' => '没有物料数据']
            ]);
        }
        try {
            $data = [];
            foreach ($items as $item) {
                $a = [
                    'company' => $item->company,
                    'goods_id' => $item->goods_id,
                    'qty' => $item->qty,
                    'location' => $item->location,
                    'remark' => $item->remark,
                ];
                array_push($data, $a);
            }
            $res = $this->sto_model->stock_out($recipient_company, $recipient_dept, $recipient_name, $sto_date, $data);
            // $this->role_->delete('a');
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'stock_out',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'sto_id = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'stock_out',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'sto_id = ' . $id
        ]);

        return json_encode([
            'status' => 'success',
        ]);
    }

    public function sto_print_data($tgl)
    {
        return json_encode($this->sto_model->sto_print_data($tgl));
    }

    public function sto_print_array()
    {
        $tgl = $this->request->getPost("datefilter");
        $data['rows'] = $this->sto_model->sto_print_data($tgl);
        return view('tyck/stock_out/p_sto_print', $data);
    }
}
