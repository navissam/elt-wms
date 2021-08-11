<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Permission_model;

class Permission extends BaseController
{
    protected $permissionModel;
    public function __construct()
    {
        $this->permissionModel = new Permission_model();
    }

    public function index()
    {
        $data['title'] = '授权管理';
        $data['active']['system']['permission'] = true;
        return view('permission/v_perm_index', $data);
    }


    public function getAll()
    {
        return json_encode($this->permissionModel->findAll());
    }
    public function getDeleted()
    {
        return json_encode($this->permissionModel->onlyDeleted()->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('permID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/permission');
        }
        $validation =  \Config\Services::validation();

        $valid = [
            'name' => [
                'rules' => 'required|is_unique[permission.name]',
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '名称已使用过'
                ]
            ],
        ];
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'name-inv',
                'errors' => $validation->getErrors()
            ]);
        }
        try {
            $this->permissionModel->insert($this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'permission',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'permID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'permission',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'permID = ' . $id
        ]);

        return json_encode([
            'status' => 'success',
        ]);
    }

    public function update()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/permission');
        }

        $validation =  \Config\Services::validation();

        $id = $this->request->getPost('permID');
        $name = $this->request->getPost('name');
        $nameLama = $this->request->getPost('name_ori');
        $ruleName = ($nameLama === $name) ? '' : '|is_unique[permission.name]';

        $valid = [
            'name' => [
                'rules' => 'required' . $ruleName,
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '名称已使用过'
                ]
            ],
        ];

        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'name-inv',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $this->permissionModel->update($this->request->getPost('permID'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'permission',
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
            'controller' => 'permission',
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

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/permission');
        }
        try {
            $id = $this->request->getPost('permID');
            $this->permissionModel->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'permission',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'permID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'permission',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'permID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function restore()
    {

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/permission');
        }
        try {
            $id = $this->request->getPost('permID');
            $this->permissionModel->restore($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'permission',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'permID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'permission',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'permID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
