<?php

namespace App\Models;

use CodeIgniter\Model;

class RolePerm_model extends Model
{
    protected $table      = 'role_permission';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['roleID', 'permID'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function unAssign($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('role_permission');
        $builder->where('roleID', $id);
        $builder->delete();
        return $builder;
    }
}
