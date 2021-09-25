<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Stock_out_model extends Model
{
    protected $table      = 'tyck_stock_out_d';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['sto_id', 'company', 'goods_id', 'qty', 'location', 'remark'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_h');
        $builder->where(['deleted_at' => null]);
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
        if ($company != null) {
            $builder->where('tyck_dept.companyID', $company);
        }
        $builder->where(['tyck_dept.deleted_at' => null]);
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

    public function stock_out($recipient_company, $recipient_dept, $recipient_name, $sto_date, $data)
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();
        $rec = $db->table('tyck_stock_out_d');
        $rec->insertBatch($data);

        $sto = $db->table('tyck_stock_out_h');
        $sto->insert([
            'recipient_company' => $recipient_company,
            'recipient_dept' => $recipient_dept,
            'recipient_name' => $recipient_name,
            'sto_date' => $sto_date,
        ]);

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }

    public function stock_out_update($recipient_company, $recipient_dept, $recipient_name, $sto_id, $sto_date, $date, $deleted, $data)
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();
        $rec = $db->table('tyck_stock_out_d');
        $rec->updateBatch($data, 'id');

        $del = $db->table('tyck_stock_out_d');
        $del->whereIn('id', $deleted);
        $del->update(['deleted_at' => $date]);

        $sto = $db->table('tyck_stock_out_h');
        $sto->where('sto_id', $sto_id);
        $sto->update([
            'recipient_company' => $recipient_company,
            'recipient_dept' => $recipient_dept,
            'recipient_name' => $recipient_name,
            'sto_date' => $sto_date,
            'updated_at' => $date,
        ]);

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }

    public function sto_print_data($tgl)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_report');
        $builder->where('sto_date', $tgl);
        return $builder->get()->getResultArray();
    }

    public function sto_list_data($start, $finish)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_report');
        $builder->where('sto_date >=', $start);
        $builder->where('sto_date <=', $finish);
        $builder->groupBy('sto_id');
        return $builder->get()->getResultArray();
    }

    public function check_stock($goods_id, $company, $location)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory');
        $builder->select('qty as inv_qty');
        $builder->where('goods_id', $goods_id);
        $builder->where('company', $company);
        $builder->where('location', $location);
        return $builder->get()->getResultArray();
    }

    public function check_id($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_d');
        $builder->select('qty');
        $builder->where('id', $id);
        return $builder->get()->getResultArray();
    }

    public function check_updated($sto_id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_h');
        $builder->select('updated_at');
        $builder->where('sto_id', $sto_id);
        return $builder->get()->getResultArray();
    }

    public function sto_detail($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_report');
        $builder->where('sto_id', $id);
        $builder->where('deleted_at is null');
        return $builder->get()->getResultArray();
    }

    public function sto_edit_h($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_h');
        $builder->select([
            'tyck_stock_out_h.*',
            'tyck_company.nameInd as nameInd',
            'tyck_company.nameMan as nameMan',
        ]);
        $builder->join('tyck_company', 'tyck_company.companyID = tyck_stock_out_h.recipient_company', 'left');
        $builder->where('tyck_stock_out_h.sto_id', $id);
        return $builder->get()->getResultArray();
    }

    // public function sto_edit_d($id)
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('tyck_stock_out_report');
    //     $builder->where('sto_id', $id);
    //     return $builder->get()->getResultArray();
    // }
}
