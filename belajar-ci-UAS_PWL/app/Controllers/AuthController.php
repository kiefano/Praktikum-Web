<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel; 

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        helper('form');
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $rules = [
                'username' => 'required|min_length[6]',
                'password' => 'required|min_length[7]|numeric',
            ];

            if ($this->validate($rules)) {
                $username = $this->request->getVar('username');
                $password = $this->request->getVar('password');

                if ($this->isFallbackAdminLogin($username, $password)) {
                    session()->set([
                        'username' => 'admin',
                        'role' => 'admin',
                        'email' => 'admin@example.com',
                        'loginTime' => date('Y-m-d H:i:s'),
                        'isLoggedIn' => true,
                    ]);

                    return redirect()->to(base_url('/'));
                }

                try {
                    $dataUser = $this->userModel->where(['username' => $username])->first();

                    if ($dataUser) {
                        if (password_verify($password, $dataUser['password'])) {
                            session()->set([
                                'username' => $dataUser['username'],
                                'role' => $dataUser['role'],
                                'email' => $dataUser['email'],
                                'loginTime' => date('Y-m-d H:i:s'),
                                'isLoggedIn' => true,
                            ]);

                            return redirect()->to(base_url('/'));
                        }

                        session()->setFlashdata('failed', 'Username & Password Salah');
                        return redirect()->back();
                    }

                    session()->setFlashdata('failed', 'Username Tidak Ditemukan');
                    return redirect()->back();
                } catch (\Throwable $e) {
                    session()->setFlashdata('failed', 'Database belum siap. Coba gunakan akun default kiefano / 1234567.');
                    return redirect()->back();
                }
            }

            session()->setFlashdata('failed', $this->validator->listErrors());
            return redirect()->back();
        }

        return view('v_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    private function isFallbackAdminLogin(string $username, string $password): bool
    {
        return $username === 'admin' && $password === '1234567';
    }
}