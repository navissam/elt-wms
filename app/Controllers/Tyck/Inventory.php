<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Inventory_model;

class Inventory extends BaseController
{
    protected $inventoryModel;
    public function __construct()
    {
        $this->inventoryModel = new Inventory_model();
    }
    public function index()
    {
        $data['active']['inventory']['inventory'] = true;
        return view('tyck/inventory/v_inventory_index', $data);
    }

    public function getAll()
    {
        return json_encode([
            'data' => $this->inventoryModel->getAll()
        ]);
    }

    public function getAllQty()
    {
        return json_encode($this->inventoryModel->getAllQty());
    }

    public function getLocation()
    {
        return json_encode($this->inventoryModel->getLocation());
    }

    public function update()
    {
        $id = $this->request->getPost('inv_id');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/inventory');
        }
        try {
            $this->inventoryModel->update($this->request->getPost('inv_id'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'inv',
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
            'controller' => 'inv',
            'method' => 'update',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function switch()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/inventory');
        }

        $id = $this->request->getPost('inv_id');
        $company = $this->request->getPost('company');
        $goods_id = $this->request->getPost('goods_id');
        $from_location = $this->request->getPost('location');
        $to_location = $this->request->getPost('location2');
        // $qtyOld = $this->request->getPost('qty');
        $qty = $this->request->getPost('qty2');
        $remark = $this->request->getPost('remark');
        // $left = $qtyOld - $qty;
        // dd($left);
        try {
            // $data = [
            //     'company' => $company,
            //     'goods_id' => $goods_id,
            //     'qty' => $qty,
            //     'location' => $to_location,
            //     'remark' => $remark,
            // ];
            // $data2 = [
            //     'company' => $company,
            //     'goods_id' => $goods_id,
            //     'qty' => $qty,
            //     'location' => $from_location,
            //     'remark' => $remark,
            // ];
            $this->inventoryModel->switch($company, $goods_id, $from_location, $to_location, $qty, $remark);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'inv',
                'method' => 'switch',
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
            'controller' => 'inv',
            'method' => 'switch',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
