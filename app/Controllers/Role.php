<?php

namespace App\Controllers;

use App\Models\Role_model;
use App\Models\RolePerm_model;

class Role extends BaseController
{
    protected $role_m, $rp_m;
    public function __construct()
    {
        $this->role_m = new Role_model();
        $this->rp_m = new RolePerm_model();
    }
    public function index()
    {
        $data['active']['system']['role'] = true;
        return view('role/v_role_main', $data);
    }

    public function getAll()
    {
        return json_encode($this->role_m->findAll());
    }
    public function getDeleted()
    {
        return json_encode($this->role_m->onlyDeleted()->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('roleID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/role');
        }
        $validation =  \Config\Services::validation();
        $rule = [
            'name' => [
                'rules' => 'required|is_unique[role.name]',
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '角色名称已使用过'
                ]
            ]
        ];

        $validation->setRules($rule);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }
        try {
            $this->role_m->insert($this->request->getPost());
            // $this->role_->delete('a');
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'role',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'roleID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'role',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'roleID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }


    public function update()
    {
        $id = $this->request->getPost('roleID');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/role');
        }
        $name = $this->request->getPost('name');
        $ori_name = $this->request->getPost('name_ori');
        $unique = ($name === $ori_name) ? '' : '|is_unique[role.name]';

        $validation =  \Config\Services::validation();
        $rule = [
            'name' => [
                'rules' => 'required' . $unique,
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '角色名称已使用过'
                ]
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ]
        ];

        $validation->setRules($rule);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }
        try {
            $this->role_m->update($this->request->getPost('roleID'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'role',
                'method' => 'update',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => json_encode($id),
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'role',
            'method' => 'update',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);

        return json_encode([
            'status' => 'success',
        ]);
    }

    public function delete()
    {
        $id = $this->request->getPost('roleID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/role');
        }

        try {
            $this->role_m->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'role',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'roleID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'role',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'roleID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function restore()
    {
        $id = $this->request->getPost('roleID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/role');
        }

        try {
            $this->role_m->restore($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'role',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'roleID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'role',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'roleID = ' . $id
        ]);

        return json_encode([
            'status' => 'success',
        ]);
    }

    public function permission($id)
    {
        return json_encode($this->role_m->permission($id));
    }

    public function assign()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/role');
        }
        try {
            $roleID = $this->request->getPost('roleID');
            $permIDs = $this->request->getPost('permIDs');
            $this->rp_m->unAssign($roleID);
            if ($permIDs !== null) {
                $data = [];
                foreach ($permIDs as $permID) {
                    array_push($data, ['roleID' => $roleID, 'permID' => $permID]);
                }
                $this->rp_m->insertBatch($data);
            }
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }

        return json_encode([
            'status' => 'success',
        ]);
    }
}
