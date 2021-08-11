<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User_model;

class Account extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new User_model();
    }

    public function changepass()
    {
        $data['title'] = '更改密码';
        return view('account/v_changepass_index', $data);
    }

    public function updatepass()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/');
        }

        $id = $this->request->getPost('userID');
        $validation =  \Config\Services::validation();
        $valid = [
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'newpass' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],

            'newpass2' => [
                'rules' => 'required|matches[newpass]',
                'errors' => [
                    'required' => '不允许为空',
                    'matches' => '密码不一样'
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
            $userID = session()->get('userID');
            $password = $this->request->getPost('password');
            $newpass = $this->request->getPost('newpass');
            $user = $this->userModel->find($userID);
            $pass = $user['password'];
            $verify = password_verify($password, $pass);
            if ($verify == true) {
                $this->userModel->update($userID, ['password' => password_hash($newpass, PASSWORD_DEFAULT)]);
                $this->syslog->insert([
                    'controller' => 'account',
                    'method' => 'updatepass',
                    'userID' => session()->get('userID') ?? '',
                    'status' => 1,
                ]);
                return json_encode([
                    'status' => 'success',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'msg' => '请输入正确的当前密码'
                ]);
            }
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'account',
                'method' => 'updatepass',
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
    }
}
