<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Stock_out_model extends Model
{
    protected $table      = 'tyck_stock_out_h';
    protected $primaryKey = 'sto_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['recipient_company', 'recipient_dept', 'recipient_name'];

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
        $builder = $db->table($this->table);
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

    public function stock_out($recipient_company, $recipient_dept, $recipient_name, $sto_date, $items)
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();
        $rec = $db->table('tyck_stock_out_d');
        $rec->insertBatch($items);

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

    public function sto_print_data($tgl)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_report');
        $builder->where('sto_date', $tgl);
        return $builder->get()->getResultArray();
    }
}
