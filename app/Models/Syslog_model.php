<?php

namespace App\Models;

use CodeIgniter\Model;

class Syslog_model extends Model
{
    protected $table      = 'syslog';
    protected $primaryKey = 'syslogID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['userID', 'controller', 'method', 'timestamp', 'data', 'response', 'status'];

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getAll()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['syslog.*', 'user.name as user_name']);
        $builder->join('user', 'syslog.userID = user.userID', 'left');
        $builder->where('syslog.timestamp >', date('Y-m-d'));
        return $builder->get()->getResultArray();
    }

    public function getByDate($start, $finish)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select(['syslog.*', 'user.name as user_name']);
        $builder->join('user', 'syslog.userID = user.userID', 'left');
        $builder->where('syslog.timestamp >=', $start);
        $builder->where('syslog.timestamp <=', $finish);
        return $builder->get()->getResultArray();
    }

    public function lastLogin($user)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('userID, timestamp');
        $builder->where('method', 'login');
        $builder->where('userID', $user);
        $builder->orderBy('timestamp', 'DESC');
        return $builder->get()->getFirstRow('array');
    }
}
