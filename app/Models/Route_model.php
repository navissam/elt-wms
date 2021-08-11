<?php

namespace App\Models;

use CodeIgniter\Model;

class Route_model extends Model
{
    protected $table      = 'route';
    protected $primaryKey = 'routeID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['httpMethod', 'url', 'contMeth', 'ord', 'permID', 'status'];

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

    // public function getPermission()
    // {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('permission');
    //     $builder->where('status <>', '0');
    //     $builder->where(['deleted_at' => null]);
    //     return $builder->get()->getResult('array');
    // }

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['route.*', 'permission.name as perm_name']);
        $builder->join('permission', 'permission.permID = route.permID', 'left');
        $builder->where(['route.deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getAllDeleted()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['route.*', 'permission.name as perm_name']);
        $builder->join('permission', 'permission.permID = route.permID', 'left');
        $builder->where(['route.deleted_at is not' => null]);
        return $builder->get()->getResultArray();
    }
}
