<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Scrap_model extends Model
{
    protected $table      = 'tyck_scrap';
    protected $primaryKey = 'scr_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['company', 'goods_id', 'location', 'qty', 'scr_date', 'reason', 'applyPIC', 'verifyPIC', 'remark'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

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
}
