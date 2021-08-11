<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'userID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['empID', 'name', 'roleID', 'wh_id', 'password', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    // public function getRole()
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('role');
    //     $builder->where('status <>', '0');
    //     $builder->where(['deleted_at' => null]);
    //     return $builder->get()->getResult('array');
    // }

    // public function getDept()
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('dept');
    //     $builder->where(['deleted_at' => null]);
    //     return $builder->get()->getResult('array');
    // }

    // public function getStatus()
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table($this->table);
    //     $builder->select('*, concat(userID,\'-\',status) as concate');
    //     // $builder->select(*,['userID', '-', 'status']);
    //     return $builder->get()->getResult('array');
    // }

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select([
            'user.*', 'concat(user.userID,\'-\',user.status) as concate',
            'role.name as role_name',
            // 'dept.deptName as dept_name',
        ]);
        $builder->join('role', 'role.roleID = user.roleID', 'left');
        // $builder->join('dept', 'dept.deptID = user.deptID', 'left');
        // $builder->where('user.roleID <>', 14);
        return $builder->get()->getResultArray();
    }
}
