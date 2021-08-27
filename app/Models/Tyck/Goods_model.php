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

    protected $allowedFields = ['goods_id', 'name_type', 'unit', 'safety'];

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

    public function getHistory()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_goods_history');
        return $builder->get()->getResultArray();
    }

    public function record($goods_id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_goods_record');
        $builder->where('goods_id', $goods_id);
        return $builder->get()->getResultArray();
    }
}
