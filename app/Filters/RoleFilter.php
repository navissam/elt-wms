<?php

namespace App\Filters;

use App\Models\Role_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\User_model;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            if (isset($_COOKIE["id"]) && isset($_COOKIE["key"])) {
                $id = base64_decode($_COOKIE["id"]);
                $model = new User_model();
                $data = $model->find($id);
                if ($data == null) {
                    return redirect()->to('/login');
                }
                if (hash('sha256', $data['empID']) !== $_COOKIE["key"]) {
                    return redirect()->to('/login');
                }
                if ($data['status'] == 0) {
                    session()->setFlashdata('error', '账号已被拉黑');
                    return redirect()->to('/login');
                }

                $ses_data = [
                    'userID' => $data['userID'],
                    'empID' => $data['empID'],
                    'name' => $data['name'],
                    'roleID' => $data['roleID'],
                    'logged_in' => TRUE
                ];
                session()->set($ses_data);
                return;
            }
            return redirect()->to('/login');
        }
        if (empty($arguments)) {
            return;
        }
        if (session()->get('logged_in')) {
            $roleID = session()->get('roleID');
            $model = new Role_model();
            $perms = $model->perm($roleID);
            $permName = [];
            foreach ($perms as $perm) {
                array_push($permName, $perm['name']);
            }
            if (in_array($arguments[0], $permName)) {
                return;
            } else {
                return redirect()->to('/');
            }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
