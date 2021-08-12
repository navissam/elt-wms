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
        $data['active']['inv']['inventory'] = true;
        return view('tyck/inventory/v_inventory_index', $data);
    }

    public function getAll()
    {
        return json_encode($this->inventoryModel->getAll());
    }

    // public function sto_print($id = null)
    // {
    //     if (is_null($id)) {
    //         $data['sto'] = $this->inventoryModel->last_sto();
    //     } else {
    //         $data['sto'] = $this->inventoryModel->sto($id);
    //     }
    //     $data['record'] = $this->inventoryModel->record($data['sto']['recordID']);
    //     // dd($data);
    //     // return $data;
    //     return view('inventory/p_sto', $data);
    // }
    // public function scr_print($id = null)
    // {
    //     if (is_null($id)) {
    //         $data['scr'] = $this->inventoryModel->last_scr();
    //     } else {
    //         $data['scr'] = $this->inventoryModel->scr($id);
    //     }
    //     $data['record'] = $this->inventoryModel->record($data['scr']['recordID']);
    //     // dd($data);
    //     // return $data;
    //     return view('inventory/p_scr', $data);
    // }
    // public function ret_print($id = null)
    // {
    //     if (is_null($id)) {
    //         $data['ret'] = $this->inventoryModel->last_ret();
    //     } else {
    //         $data['ret'] = $this->inventoryModel->ret($id);
    //     }
    //     $data['record'] = $this->inventoryModel->record($data['ret']['recordID']);
    //     // dd($data);
    //     // return $data;
    //     return view('inventory/p_ret', $data);
    // }

    // public function record($goodsID, $locationID, $ownerID)
    // {
    //     $records = $this->inventoryModel->records($goodsID, $locationID, $ownerID);
    //     return json_encode($records);
    // }

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
        $id = $this->request->getPost('inv_id');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/inventory');
        }

        $invID = $this->request->getPost('invID');
        $ownerID = $this->request->getPost('ownerID');
        $goodsID = $this->request->getPost('goodsID');
        $fromLocationID = $this->request->getPost('locationID');
        $toLocationID = $this->request->getPost('locationID2');
        $qtyOld = $this->request->getPost('qty');
        $qty = $this->request->getPost('qty2');
        $remark = $this->request->getPost('remark');
        $recordType = $this->request->getPost('recordType');
        $left = $qtyOld - $qty;
        // $items = json_decode($this->request->getPost('items'));
        try {
            $data = [
                'ownerID' => $ownerID,
                'goodsID' => $goodsID,
                'locationID' => $toLocationID,
                'statusQty' => '+',
                'qty' => $qty,
                'remark' => $remark,
                'recordType' => $recordType,
            ];
            $data2 = [
                'ownerID' => $ownerID,
                'goodsID' => $goodsID,
                'locationID' => $fromLocationID,
                'statusQty' => '-',
                'qty' => $qty,
                'remark' => $remark,
                'recordType' => $recordType,
            ];
            $res = $this->inventoryModel->switch($ownerID, $goodsID, $fromLocationID, $toLocationID, $qty, $remark, $data, $data2, $invID, $left);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'inventory',
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
            'controller' => 'inventory',
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
