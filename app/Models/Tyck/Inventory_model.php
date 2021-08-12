<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Inventory_model extends Model
{
    protected $table      = 'tyck_inventory';
    protected $primaryKey = 'inv_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['company', 'goods_id', 'qty', 'location', 'safety', 'remark'];

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
        // $builder->select([
        //     'tyck_inventory.*',
        //     'tyck_goods.name_type as goods_name',
        // ]);
        // $builder->join('tyck_goods', 'tyck_goods.goods_id = tyck_inventory.goods_id', 'left');
        $builder->where(['tyck_inventory.deleted_at' => null]);
        return $builder->get()->getResultArray();
    }
}
