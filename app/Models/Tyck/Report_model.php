<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Report_model extends Model
{
    public function sti_report_adv($choosen, $start, $finish, $company, $goods, $location)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_in_report');
        $builder->select($choosen);
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        if ($company != null) {
            // $c = explode(",", $company);
            $builder->whereIn('company', $company);
        }
        if ($goods != null) {
            // $g = explode(",", $goods);
            $builder->whereIn('goods_id', $goods);
        }
        if ($location != null) {
            // $l = explode(",", $location);
            $builder->whereIn('location', $location);
        }
        return $builder->get()->getResultArray();
    }

    public function sti_report_basic($start, $finish)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_in_report');
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        return $builder->get()->getResultArray();
    }

    public function getGoods()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_goods');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getLocation()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory');
        $builder->select('location');
        $builder->distinct('location');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getCompany()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('company');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }
}
