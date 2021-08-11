<?php

namespace App\Models;

use CodeIgniter\Model;

class Role_model extends Model
{
    protected $table      = 'role';
    protected $primaryKey = 'roleID';

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

    // public function search($key)
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table($this->table);
    //     $builder->like('name', $key);
    //     $builder->orLike('empID', $key);
    //     return $builder->get()->getResult('array');
    // }

    public function restore($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->where($this->primaryKey, $id);
        $builder->update(['deleted_at' => null]);
        return $builder;
    }
    public function perm($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('permission');
        $builder->select(['permission.name']);
        $builder->join('role_permission', 'role_permission.permID = permission.permID AND role_permission.roleID = ' . $id, 'inner');
        $builder->where('permission.deleted_at', null);
        // dd($builder->getCompiledSelect());
        // dd($builder->get()->getResultArray());
        return $builder->get()->getResultArray();
    }
    public function permission($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('permission');
        $builder->select(['permission.permID', 'permission.name', 'IF(SUM(IF(role.roleID IS NOT null, 1, 0)) = 0, null,1) AS selected']);
        $builder->join('role_permission', 'role_permission.permID = permission.permID', 'left');
        $builder->join('role', 'role_permission.roleID = role.roleID AND role.roleID = ' . $id, 'left');
        $builder->where('permission.deleted_at', null);
        $builder->groupBy('permission.permID');
        // dd($builder->getCompiledSelect());
        // dd($builder->get()->getResultArray());
        return $builder->get()->getResultArray();
    }
    public function unAssign($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('role_permission');
        $builder->where('roleID', $id);
        $builder->delete();
        return $builder;
    }
}
