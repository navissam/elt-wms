<?php

namespace App\Models\Tyck;

use CodeIgniter\Model;

class Report_model extends Model
{
    public function getGoods()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_goods');
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

    public function getLocation()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_inventory');
        $builder->select('location');
        $builder->distinct('location');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getRDept()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_h');
        $builder->select('recipient_dept');
        $builder->distinct('recipient_dept');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getRName()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_h');
        $builder->select('recipient_name');
        $builder->distinct('recipient_name');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getRetDept()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_return');
        $builder->select('ret_dept');
        $builder->distinct('ret_dept');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    public function getRetName()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_return');
        $builder->select('ret_name');
        $builder->distinct('ret_name');
        $builder->where(['deleted_at' => null]);
        return $builder->get()->getResultArray();
    }

    // report basic
    public function sti_report_basic($start, $finish)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_in_report');
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        return $builder->get()->getResultArray();
    }

    public function sto_report_basic($start, $finish)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_report');
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        return $builder->get()->getResultArray();
    }

    public function ret_report_basic($start, $finish)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_return_report');
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        return $builder->get()->getResultArray();
    }

    // report advanced
    public function sti_report_adv($choosen, $start, $finish, $company, $goods, $location)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_in_report');
        $builder->select($choosen);
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        if ($company != null) {
            $builder->whereIn('company', $company);
        }
        if ($goods != null) {
            $builder->whereIn('goods_id', $goods);
        }
        if ($location != null) {
            $builder->whereIn('location', $location);
        }
        return $builder->get()->getResultArray();
    }

    public function sto_report_adv($choosen, $start, $finish, $company, $goods, $location, $r_company, $r_dept, $r_name)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_stock_out_report');
        $builder->select($choosen);
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        if ($company != null) {
            $builder->whereIn('company', $company);
        }
        if ($goods != null) {
            $builder->whereIn('goods_id', $goods);
        }
        if ($location != null) {
            $builder->whereIn('location', $location);
        }
        if ($r_company != null) {
            $builder->whereIn('recipient_company', $r_company);
        }
        if ($r_dept != null) {
            $builder->whereIn('recipient_dept', $r_dept);
        }
        if ($r_name != null) {
            $builder->whereIn('recipient_name', $r_name);
        }
        return $builder->get()->getResultArray();
    }

    public function ret_report_adv($choosen, $start, $finish, $company, $goods, $location, $r_company, $r_dept, $r_name)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('tyck_return_report');
        $builder->select($choosen);
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $finish);
        if ($company != null) {
            $builder->whereIn('company', $company);
        }
        if ($goods != null) {
            $builder->whereIn('goods_id', $goods);
        }
        if ($location != null) {
            $builder->whereIn('location', $location);
        }
        if ($r_company != null) {
            $builder->whereIn('ret_company', $r_company);
        }
        if ($r_dept != null) {
            $builder->whereIn('ret_dept', $r_dept);
        }
        if ($r_name != null) {
            $builder->whereIn('ret_name', $r_name);
        }
        return $builder->get()->getResultArray();
    }
}
