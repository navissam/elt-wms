<?php

namespace App\Controllers\Tyck;

use App\Controllers\BaseController;
use App\Models\Tyck\Dept_model;

class Dept extends BaseController
{
    protected $deptModel;
    public function __construct()
    {
        $this->deptModel = new Dept_model();
    }

    public function index()
    {
        $data['title'] = '部门管理';
        $data['active']['basic']['dept'] = true;
        return view('tyck/dept/v_dept_index', $data);
    }

    public function getAll()
    {
        return json_encode($this->deptModel->getAll());
    }

    public function getDeleted()
    {
        return json_encode($this->deptModel->getAllDeleted());
    }

    public function getCompany()
    {
        return json_encode($this->deptModel->getCompany());
    }

    public function getParent()
    {
        return json_encode($this->deptModel->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('deptID');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/dept');
        }

        $validation =  \Config\Services::validation();

        $valid = [
            'deptName' => [
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
            $this->deptModel->insert([
                'deptName' => $this->request->getPost('deptName'),
                'companyID' => $this->request->getPost('companyID'),
            ]);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'dept',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'deptID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'dept',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'deptID = ' . $id
        ]);

        return json_encode([
            'status' => 'success',
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('deptID');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/dept');
        }

        $validation =  \Config\Services::validation();

        $valid = [
            'deptName' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
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
            $data = [
                'deptName' => $this->request->getPost('deptName'),
                'companyID' => $this->request->getPost('companyID'),
            ];

            $this->deptModel->update($id, $data);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'dept',
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
            'controller' => 'dept',
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
            return redirect()->to('/tyck/dept');
        }
        try {
            $id = $this->request->getPost('deptID');
            $this->deptModel->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'dept',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'deptID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'dept',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'deptID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function restore()
    {

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/tyck/dept');
        }
        try {
            $id = $this->request->getPost('deptID');
            $this->deptModel->restore($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'dept',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'deptID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'dept',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'deptID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
