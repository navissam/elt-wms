<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Sti_temp_model extends Model
{
    protected $table      = 'tyck_stock_in_temp';
    protected $primaryKey = 'sti_id';

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
        'location',
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
        $builder = $db->table('tyck_sti_temp_duplicate');
        return $builder->get()->getResult('array');
    }
    public function getInconsistent()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_sti_temp_inconsistent');
        return $builder->get()->getResult('array');
    }

    public function getNewGoods()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_sti_temp_new_goods');
        return $builder->get()->getResult('array');
    }

    public function getNewLocation()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_sti_temp_new_location');
        return $builder->get()->getResult('array');
    }

    public function getNewCompany()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_sti_temp_new_company');
        return $builder->get()->getResult('array');
    }
    public function getInvalidQty()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_sti_temp_invalid_qty');
        return $builder->get()->getResult('array');
    }

    public function truncate()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->truncate();
    }

    public function insert_goods()
    {
        return $this->db->query('INSERT `tyck_goods` (goods_id,name_type,unit) SELECT goods_id,name_type,unit FROM `tyck_sti_temp_new_goods` WHERE 1');
    }

    public function migrate()
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();

        $db->query('INSERT `tyck_stock_in_rec` (`company`, `goods_id`, `qty`, `location`, `remark`)
        SELECT `company`, `goods_id`, `qty`, `location`, `remark`
        FROM `tyck_stock_in_temp` WHERE `deleted_at` is null;');

        $db->query('TRUNCATE TABLE `tyck_stock_in_temp`');

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }
}
