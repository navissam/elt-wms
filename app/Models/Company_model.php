<?php

namespace App\Models;

use CodeIgniter\Model;

class Company_model extends Model
{
    protected $table      = 'company';
    protected $primaryKey = 'companyID';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['companyID', 'nameInd', 'nameMan', 'logo'];

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
