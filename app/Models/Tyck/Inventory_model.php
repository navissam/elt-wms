<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Inventory_model extends Model
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

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory_all');
        return $builder->get()->getResultArray();
    }

    public function getLocation()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('location');
        $builder->distinct('location');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function switch($company, $goods_id, $from_location, $to_location, $qty, $remark)
    {
        $db      = \Config\Database::connect();
        // $this->db->transBegin();
        $db->transBegin();
        // $rec = $db->table('record');
        // $rec->insert($data);
        // $rec->insert($data2);

        // $inv = $db->table($this->table);
        // $inv->where('inv_id', $id);
        // $inv->update(['qty' => $left]);

        $swc = $db->table('tyck_switch');
        $swc->insert([
            'company' => $company,
            'goods_id' => $goods_id,
            'qty' => $qty,
            'from_location' => $from_location,
            'to_location' => $to_location,
            'remark' => $remark,
        ]);

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }
}
