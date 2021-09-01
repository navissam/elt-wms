<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Dept_model extends Model
{
    protected $table      = 'tyck_dept';
    protected $primaryKey = 'deptID';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['deptID', 'deptName', 'parentID', 'companyID'];

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

    public function getCompany()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_company');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResult('array');
    }

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['tyck_dept.*', 'tyck_company.nameInd as company_name']);
        $builder->join('tyck_company', 'tyck_company.companyID = tyck_dept.companyID', 'left');
        $builder->where(['tyck_dept.deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getAllDeleted()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['tyck_dept.*', 'tyck_company.nameInd as company_name']);
        $builder->join('tyck_company', 'tyck_company.companyID = tyck_dept.companyID', 'left');
        $builder->where(['tyck_dept.deleted_at is not' => null]);
        return $builder->get()->getResultArray();
    }
}
