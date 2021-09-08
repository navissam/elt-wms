<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Request_model extends Model
{

    public function getQty($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory');
        $builder->select('qty');
        $builder->where('inv_id', $id);
        return $builder->get()->getResultArray();
    }

    public function getCompany()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_company');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getDept($company)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_dept');
        $builder->where(['companyID' => $company]);
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getInv()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory');
        $builder->select([
            'tyck_inventory.*',
            'tyck_goods.name_type as goods_name',
            'tyck_goods.unit as unit',
        ]);
        $builder->join('tyck_goods', 'tyck_goods.goods_id = tyck_inventory.goods_id', 'left');
        // $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function request($data)
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();
        $rec = $db->table('tyck_request_temp');
        $rec->insertBatch($data);

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }

    public function truncate()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_request_temp');
        return $builder->truncate();
    }

    public function req_dept($d)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_dept');
        $builder->where('deptID', $d);
        return $builder->get()->getResultArray();
    }

    public function req_company($company)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_company');
        $builder->where('companyID', $company);
        return $builder->get()->getResultArray();
    }

    public function req_print_data()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_request_temp');
        return $builder->get()->getResultArray();
    }
}
