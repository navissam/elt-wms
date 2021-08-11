<?php

namespace App\Models;

use CodeIgniter\Model;

class Permission_model extends Model
{
    protected $table      = 'permission';
    protected $primaryKey = 'permID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function restore($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->where($this->primaryKey, $id);
        $builder->update(['deleted_at' => null]);
        return $builder;
    }
}
