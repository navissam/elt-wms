<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Safety_model extends Model
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

    public function getAllTyck()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_under_safety_union');
        $builder->where('qty >', 0);
        return $builder->get()->getResultArray();
    }
}
