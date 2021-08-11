<?php

namespace App\Controllers;

use App\Models\User_model;
use App\Models\Syslog_model;

class Auth extends BaseController
{
    protected $user_m, $syslog_m;
    public function __construct()
    {
        $this->user_m = new User_model();
        $this->syslog_m = new Syslog_model();
    }
    public function index()
    {

        return view('auth/v_login');
    }

    public function login()
    {
        if ($this->request->getMethod() == 'post') {
            $id = $this->request->getPost('id');
            $pass = $this->request->getPost('pass');
            // $data = $this->user_m->where('empID', $id);
            $data = $this->user_m->where('empID', $id)->first();
            // dd($data);
            if ($data == null) {
                session()->setFlashdata('error', '账号无效');
                return redirect()->to('/login');
            } else {
                $user = $data['userID'];
            }
            $last = $this->syslog_m->lastLogin($user);
            if ($last == null) {
                $l = '';
            } else {
                $l = $last['timestamp'];
            }

            if ($data) {
                $verify_pass = password_verify($pass, $data['password']);
                if ($verify_pass) {
                    if ($data['status'] == 0) {
                        session()->setFlashdata('error', '账号已被拉黑');
                        return redirect()->to('/login');
                    }

                    $ses_data = [
                        'userID' => $data['userID'],
                        'empID' => $data['empID'],
                        'name' => $data['name'],
                        'roleID' => $data['roleID'],
                        'lastLogin' => $l,
                        'logged_in' => TRUE,
                    ];
                    session()->set($ses_data);
                    $this->syslog->insert([
                        'controller' => 'auth',
                        'method' => 'login',
                        'userID' => session()->get('userID'),
                        // 'empID' => $empID,
                        'status' => 1,
                    ]);
                    $remember = $this->request->getPost('remember');

                    if ($remember) {
                        $userid = base64_encode($data['userID']);
                        $empid = hash('sha256', $data['empID']);
                        setcookie('id', $userid, time() + 365 * 24 * 60 * 60, '/');
                        setcookie('key', $empid, time() + 365 * 24 * 60 * 60, '/');
                    }

                    return redirect()->to('/');
                } else {
                    session()->setFlashdata('error', '密码错误');
                    return redirect()->to('/login');
                }
            } else {
                session()->setFlashdata('error', '账号无效');
                return redirect()->to('/login');
            }
        }
    }
    public function logout()
    {
        if (session()->get('empID') != null) {
            $this->syslog->insert([
                'controller' => 'auth',
                'method' => 'logout',
                // 'empID' => session()->get('empID'),
                'userID' => session()->get('userID'),
                'status' => 1,
            ]);
        }
        session()->destroy();

        if (isset($_COOKIE['id'])) {
            setcookie('id', '', time() - 3600, '/');
            setcookie('key', '', time() - 3600, '/');
        }
        return redirect()->to('/login');
    }
}
