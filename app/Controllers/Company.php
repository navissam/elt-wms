<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Company_model;

class Company extends BaseController
{
    protected $companyModel;
    public function __construct()
    {
        $this->companyModel = new Company_model();
    }

    public function index()
    {
        $data['title'] = '公司管理';
        $data['row'] = $this->companyModel->findAll();
        $data['active']['basic']['company'] = true;
        return view('company/v_company_index', $data);
    }


    public function getAll()
    {
        return json_encode($this->companyModel->findAll());
    }
    public function getDeleted()
    {
        return json_encode($this->companyModel->onlyDeleted()->findAll());
    }

    public function save()
    {
        $id = $this->request->getPost('companyID');
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/company');
        }
        $validation =  \Config\Services::validation();
        $valid = [
            'companyID' => [
                'rules' => 'required|is_unique[company.companyID]',
                'errors' => [
                    'required' => '不允许为空',
                    'is_unique' => '公司ID已用过'
                ]
            ],
            'nameInd' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'nameMan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
        ];
        if ($this->request->getFile('logo') != null) {
            $valid['logo'] = [
                'rules' => 'is_image[logo]|max_size[logo,512]|mime_in[logo,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => '尺寸太大',
                    'is_image' => '不是图片',
                    'mime_in' => '不是图片'
                ]
            ];
        }
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            //ambil gambar
            $fileLogo = $this->request->getFile('logo');
            //apakah tidak ada gambar yang diupload
            if ($fileLogo == null || $fileLogo->getError() == 4) {
                $namaLogo = false;
            } else {
                //generate nama Logo random
                $namaLogo = $this->request->getPost('companyID') . '.' . $fileLogo->getExtension();
                //pindahkan file ke folder img
                $fileLogo->move(FCPATH . 'img', $namaLogo);
            }
            $data = [
                'companyID' => $this->request->getPost('companyID'),
                'nameInd' => $this->request->getPost('nameInd'),
                'nameMan' => $this->request->getPost('nameMan')
            ];
            if ($namaLogo != false) {
                $data['logo'] = $namaLogo;
            } else {
                $data['logo'] = 'default.png';
            }

            $this->companyModel->insert($data);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'company',
                'method' => 'save',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'companyID = ' . $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'company',
            'method' => 'save',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'companyID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('companyID');

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/company');
        }
        $validation =  \Config\Services::validation();
        $valid = [
            'nameInd' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
            'nameMan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '不允许为空'
                ]
            ],
        ];
        if ($this->request->getFile('logo') != null) {
            $valid['logo'] = [
                'rules' => 'is_image[logo]|max_size[logo,512]|mime_in[logo,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => '尺寸太大',
                    'is_image' => '不是图片',
                    'mime_in' => '不是图片'
                ]
            ];
        }
        $validation->setRules($valid);
        if (!$validation->run($this->request->getPost())) {
            return json_encode([
                'status' => 'invalid',
                'errors' => $validation->getErrors()
            ]);
        }
        try {
            $data = [
                'nameInd' => $this->request->getPost('nameInd'),
                'nameMan' => $this->request->getPost('nameMan')
            ];
            //ambil gambar
            $fileLogo = $this->request->getFile('logo');
            //apakah tidak ada gambar yang diupload
            if ($fileLogo == null || $fileLogo->getError() == 4) {
                $namaLogo = false;
            } else {
                //generate nama Logo random
                $namaLogo = $this->request->getPost('companyID') . '.' . $fileLogo->getExtension();
                $data['logo'] = $namaLogo;
                $oldLogo = $this->request->getPost('oldLogo');
                if ($oldLogo != 'default.png') {
                    $photo = './img/' . $oldLogo;
                    if (file_exists($photo)) {
                        if (chmod($photo, 0777)) {
                            unlink($photo);
                        };
                    }
                }
                //pindahkan file ke folder img
                $fileLogo->move(FCPATH . 'img', $namaLogo);
            }
            $this->companyModel->update($this->request->getPost('companyID'), $data);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'company',
                'method' => 'update',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => json_encode($id),
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage() . '-' . $photo
            ]);
        }
        $this->syslog->insert([
            'controller' => 'company',
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
            return redirect()->to('/company');
        }
        try {
            $id = $this->request->getPost('companyID');
            $this->companyModel->delete($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'company',
                'method' => 'delete',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'companyID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'company',
            'method' => 'delete',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'companyID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
    public function restore()
    {

        if ($this->request->getMethod() != 'post') {
            return redirect()->to('/company');
        }
        try {
            $id = $this->request->getPost('companyID');
            $this->companyModel->restore($id);
        } catch (\Exception $e) {
            $this->syslog->insert([
                'controller' => 'company',
                'method' => 'restore',
                'userID' => session()->get('userID') ?? '',
                'status' => 0,
                'data' => 'companyID = ' .  $id,
                'response' => $e->getMessage()
            ]);
            return json_encode([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
        $this->syslog->insert([
            'controller' => 'company',
            'method' => 'restore',
            'userID' => session()->get('userID') ?? '',
            'status' => 1,
            'data' => 'companyID = ' . $id
        ]);
        return json_encode([
            'status' => 'success',
        ]);
    }
}
