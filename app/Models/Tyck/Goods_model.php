<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Goods_model extends Model
{
    protected $table      = 'tyck_goods';
    protected $primaryKey = 'goods_id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['goods_id', 'name_type', 'unit'];

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
        $builder = $db->table($this->table);
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResult('array');
    }
    // public function getStoGoods()
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table($this->table);
    //     $builder->join('unit', 'unit.unitID = goods.unitID', 'inner');
    //     $builder->join('record', 'record.goodsID = goods.goodsID', 'inner');
    //     $builder->join('stock_out_rec', 'record.recordID = stock_out_rec.recordID', 'inner');
    //     $builder->select([
    //         'goods.*',
    //         'unit.name as unitName'
    //     ]);
    //     $builder->groupBy('goods.goodsID');
    //     $builder->where(['goods.deleted_at' => null]);
    //     return $builder->get()->getResult('array');
    // }

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }
}
