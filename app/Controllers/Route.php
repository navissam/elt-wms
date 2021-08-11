<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Route_model;
use App\Models\Permission_model;

class Route extends BaseController
{
    protected $routeModel, $permissionModel;
    public function __construct()
    {
        $this->routeModel = new Route_model();
        $this->permissionModel = new Permission_model();
    }

    public function index()
    {
        $data['title'] = '路由管理';
        // $data['perm'] = $this->routeModel->getPermission();
        $data['active']['system']['route'] = true;
        return view('route/v_route_index', $data);
    }

    public function getAll()
    {
        return json_encode($this->routeModel->getAll());
    }

    public function getDeleted()
    {
        // return json_encode($this->routeModel->onlyDeleted()->findAll());
        return json_encode($this->routeModel->getAllDeleted());
    }

    public function getPermission()
    {
        return json_encode($this->permissionModel->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('routeID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/route');
        }
        $validation =  \Config\Services::validation();

        $valid = [
            'httpMethod' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'url' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'contMeth' => [
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
        try {
            $this->routeModel->insert($this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'route',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'routeID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'route',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'routeID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('routeID');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/route');
        }
        $validation =  \Config\Services::validation();
        $valid = [
            'httpMethod' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'url' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'contMeth' => [
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

        try {
            $this->routeModel->update($this->request->getPost('routeID'), $this->request->getPost());
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'route',
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
            'controller' => 'route',
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
            return redirect()->to('/route');
        }
        try {
            $id = $this->request->getPost('routeID');
            $this->routeModel->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'route',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'routeID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'route',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'routeID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function restore()
    {

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/route');
        }
        try {
            $id = $this->request->getPost('routeID');
            $this->routeModel->restore($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'route',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'routeID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'route',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'routeID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
