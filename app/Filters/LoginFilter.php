<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\User_model;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!(session()->get('logged_in'))) {
            if (isset($_COOKIE["id"]) && isset($_COOKIE["key"])) {
                $id = base64_decode($_COOKIE["id"]);
                $model = new User_model();
                $data = $model->find($id);
                if ($data == null) {
                    return;
                }
                if (hash('sha256', $data['empID']) !== $_COOKIE["key"]) {
                    return;
                }
                if ($data['status'] == 0) {
                    session()->setFlashdata('error', '账号已被拉黑');
                    return;
                }

                $ses_data = [
                    'userID' => $data['userID'],
                    'empID' => $data['empID'],
                    'name' => $data['name'],
                    'roleID' => $data['roleID'],
                    'logged_in' => TRUE
                ];
                session()->set($ses_data);
                // $cuslog->insert([
                //     'controller' => 'auth',
                //     'method' => 'login',
                //     'empID' => $data['empID'],
                //     'status' => 1,
                // ]);
                return redirect()->to('/');
            }
        }
        if (session()->get('logged_in')) {
            return redirect()->to('/');;
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
