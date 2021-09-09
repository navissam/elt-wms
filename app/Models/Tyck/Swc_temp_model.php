<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Swc_temp_model extends Model
{
    protected $table      = 'tyck_switch_temp';
    protected $primaryKey = 'swc_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'num',
        'company',
        'goods_id',
        'name_type',
        'unit',
        'qty',
        'from_location',
        'to_location',
        'old_stock',
        'new_stock',
        'remark'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getDuplicate()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_switch_temp_duplicate');
        return $builder->get()->getResult('array');
    }
    public function getInventoryNull()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_switch_temp_inventory_null');
        return $builder->get()->getResult('array');
    }

    public function getNewGoods()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_switch_temp_new_goods');
        return $builder->get()->getResult('array');
    }

    public function getNewLocation()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_switch_temp_new_location');
        return $builder->get()->getResult('array');
    }

    public function getNewCompany()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_switch_temp_new_company');
        return $builder->get()->getResult('array');
    }
    public function getMinQty()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_switch_temp_min_qty');
        return $builder->get()->getResult('array');
    }

    public function truncate()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->truncate();
    }

    public function migrate()
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();

        $db->query('INSERT `tyck_switch` (`company`, `goods_id`, `qty`, `from_location`, `to_location`, `old_stock`, `new_stock`, `remark`)
        SELECT `company`, `goods_id`, `qty`, `from_location`, `to_location`, `old_stock`, `new_stock`, `remark`
        FROM `tyck_switch_temp` WHERE `deleted_at` is null;');

        $db->query('TRUNCATE TABLE `tyck_switch_temp`');

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }
}
