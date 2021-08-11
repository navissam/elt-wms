<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User_model;
use App\Models\Role_model;

class User extends BaseController
{
    protected $userModel, $roleModel;
    public function __construct()
    {
        $this->userModel = new User_model();
        $this->roleModel = new Role_model();
    }

    public function index()
    {
        $data['title'] = '用户管理';
        // $data['role'] = $this->userModel->getRole();
        // $data['dept'] = $this->userModel->getDept();
        $data['active']['system']['user'] = true;
        return view('user/v_user_index', $data);
    }

    // public function getStatus()
    // {
    //     return json_encode($this->userModel->getStatus());
    // }

    public function getAll()
    {
        return json_encode($this->userModel->getAll());
    }

    public function getRole()
    {
        return json_encode($this->roleModel->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('userID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/user');
        }
        $validation =  \Config\Services::validation();

        $valid = [
            'empID' => [
                'rules' => 'required|is_unique[user.empID]',
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '工号已注册过'
                ]
            ],
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'passConfirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '不允许为空',
                    'matches' => '密码不一样'
                ]
            ],
        ];
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }
        try {
            $this->userModel->insert([
                'empID' => $this->request->getPost('empID'),
                'name' => $this->request->getPost('name'),
                'roleID' => $this->request->getPost('roleID'),
                'wh_id' => $this->request->getPost('wh_id'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ]);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'user',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'userID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'user',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'userID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function update()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/user');
        }

        $id = $this->request->getPost('userID');
        $empID = $this->request->getPost('empID');
        $empIDLama = $this->request->getPost('empID_ori');
        $ruleempID = ($empIDLama === $empID) ? '' : '|is_unique[user.empID]';

        $validation =  \Config\Services::validation();
        $valid = [
            'empID' => [
                'rules' => 'required' . $ruleempID,
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '工号已注册过'
                ]
            ],
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ]
        ];
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }
        try {
            $this->userModel->update($this->request->getPost('userID'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'user',
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
            'controller' => 'user',
            'method' => 'update',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => json_encode($id)
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function blacklist()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/user');
        }
        try {
            $id = $this->request->getPost('userID');
            $this->userModel->update($id, ['status' => 0]);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'user',
                'method' => 'blacklist',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'userID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'user',
            'method' => 'blacklist',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'userID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function restore()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/user');
        }
        try {
            $id = $this->request->getPost('userID');
            $this->userModel->update($id, ['status' => 1]);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'user',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'userID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'user',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'userID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function reset()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/user');
        }
        try {
            $id = $this->request->getPost('userID');
            $this->userModel->update($id, ['password' => password_hash('123', PASSWORD_DEFAULT)]);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'user',
                'method' => 'reset',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'userID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'user',
            'method' => 'reset',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'userID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
