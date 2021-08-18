<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Return_model extends Model
{
    protected $table      = 'tyck_return';
    protected $primaryKey = 'ret_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['ret_company', 'ret_dept', 'ret_name', 'ret_date', 'company', 'goods_id', 'ret_location', 'qty', 'remark'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getGoods()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_goods');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getLocation()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory');
        $builder->select('location');
        $builder->distinct('location');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }
}
