<?php

namespace App\Models;

use CodeIgniter\Model;

class Menu_model extends Model
{
    protected $table      = 'menu';
    protected $primaryKey = 'menuID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['code', 'name', 'parentCode', 'type', 'url', 'icon', 'ord', 'permID', 'status'];

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
        $builder->select(['menu.*', 'permission.name as perm_name']);
        $builder->join('permission', 'permission.permID = menu.permID', 'left');
        $builder->where(['menu.deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getAllDeleted()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['menu.*', 'permission.name as perm_name']);
        $builder->join('permission', 'permission.permID = menu.permID', 'left');
        $builder->where(['menu.deleted_at is not' => null]);
        return $builder->get()->getResultArray();
    }
}
