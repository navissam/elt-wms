<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Menu_model;
use App\Models\Permission_model;

class Menu extends BaseController
{
    protected $menuModel, $permissionModal;
    public function __construct()
    {
        $this->menuModel = new Menu_model();
        $this->permissionModel = new Permission_model();
    }

    public function index()
    {
        $data['title'] = '菜单管理';
        // $data['perm'] = $this->menuModel->getPermission();
        // $data['pcode'] = $this->menuModel->getCode();
        $data['active']['system']['menu'] = true;
        return view('menu/v_menu_index', $data);
    }


    public function getAll()
    {
        return json_encode($this->menuModel->getAll());
    }

    public function getDeleted()
    {
        // return json_encode($this->menuModel->onlyDeleted()->findAll());
        return json_encode($this->menuModel->getAllDeleted());
    }

    public function getPermission()
    {
        return json_encode($this->permissionModel->findAll());
    }

    public function getParent()
    {
        return json_encode($this->menuModel->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('menuID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/menu');
        }
        $validation =  \Config\Services::validation();

        $valid = [
            'code' => [
                'rules' => 'required|is_unique[menu.code]',
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '编码已用过'
                ]
            ],
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'icon' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'ord' => [
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
        $cekParent = $this->request->getPost('parentCode');
        if ($cekParent === '') {
            $cekParent = NULL;
        }

        $cekUrl = $this->request->getPost('url');
        if ($cekUrl === '') {
            $cekUrl = NULL;
        }

        $cekPermID = $this->request->getPost('permID');
        if ($cekPermID === '') {
            $cekPermID = NULL;
        }

        try {
            $this->menuModel->insert([
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'parentCode' => $cekParent,
                // 'parentCode' => $this->request->getPost('parentCode'),
                'type' => $this->request->getPost('type'),
                'url' => $cekUrl,
                'icon' => $this->request->getPost('icon'),
                'ord' => $this->request->getPost('ord'),
                'permID' => $cekPermID,
            ]);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'menu',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'menuID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'menu',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'menuID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function update()
    {
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/menu');
        }

        $id = $this->request->getPost('menuID');
        $code = $this->request->getPost('code');
        $codeLama = $this->request->getPost('code_ori');
        $rulecode = ($codeLama === $code) ? '' : '|is_unique[menu.code]';

        $validation =  \Config\Services::validation();
        $valid = [
            'code' => [
                'rules' => 'required' . $rulecode,
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '编码已用过'
                ]
            ],
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'parentCode' => [
                'rules' => 'differs[code]',
                'errors' => [
                    'differs' => '请输入 ' . $code . ' 以外的编码'
                ]
            ],
            'icon' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'ord' => [
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
        $cekParent = $this->request->getPost('parentCode');
        if ($cekParent === '') {
            $cekParent = NULL;
        }

        $cekUrl = $this->request->getPost('url');
        if ($cekUrl === '') {
            $cekUrl = NULL;
        }

        $cekPermID = $this->request->getPost('permID');
        if ($cekPermID === '') {
            $cekPermID = NULL;
        }
        try {
            $data = [
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'parentCode' => $cekParent,
                // 'parentCode' => $this->request->getPost('parentCode'),
                'type' => $this->request->getPost('type'),
                'url' => $cekUrl,
                'icon' => $this->request->getPost('icon'),
                'ord' => $this->request->getPost('ord'),
                'permID' => $cekPermID,
                'status' => $this->request->getPost('status'),
            ];
            $this->menuModel->update($id, $data);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'menu',
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
            'controller' => 'menu',
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
            return redirect()->to('/menu');
        }
        try {
            $id = $this->request->getPost('menuID');
            $this->menuModel->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'menu',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'menuID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'menu',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'menuID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function restore()
    {

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/menu');
        }
        try {
            $id = $this->request->getPost('menuID');
            $this->menuModel->restore($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'menu',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'menuID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'menu',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'menuID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
