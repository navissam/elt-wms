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

    // public function getGoods($company, $location)
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('tyck_goods');
    //     $builder->select([
    //         'tyck_goods.*',
    //         'tyck_inventory.company',
    //         'tyck_inventory.location',
    //         'tyck_inventory.qty',
    //     ]);
    //     $builder->join('tyck_inventory', 'tyck_goods.goods_id=tyck_inventory.goods_id');
    //     $builder->where(['tyck_inventory.company' => $company]);
    //     $builder->where(['tyck_inventory.location' => $location]);
    //     $builder->where(['tyck_goods.deleted_at' => null]);
    //     return $builder->get()->getResultArray();
    // }

    // public function getLocation($company)
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('tyck_inventory');
    //     $builder->select('location');
    //     $builder->distinct('location');
    //     $builder->where(['company' => $company]);
    //     $builder->where(['deleted_at' => null]);
    //     return $builder->get()->getResultArray();
    // }

    // public function getCompany()
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('company');
    //     $builder->where(['deleted_at' => null]);
    //     return $builder->get()->getResultArray();
    // }

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
