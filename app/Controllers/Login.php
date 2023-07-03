<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Masuk',
        ];

        return view('login', $data);
    }

    public function auth()
    {
        $admin      = new \App\Models\AdminModel();
        $designer   = new \App\Models\DesignerModel();
        $customer   = new \App\Models\CustomerModel();

        $session = session();
        $email      = $this->request->getVar('email');
        $password   = $this->request->getVar('password');

        $dataAdmin      = $admin->where('email', $email)->first();
        $dataDesigner   = $designer->where('email', $email)->first();
        $dataCustomer   = $customer->where('email', $email)->first();

        if ($dataAdmin) {
            $pass = $dataAdmin['password'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'id'        => $dataAdmin['id'],
                    'email'     => $dataAdmin['email'],
                    'tipe'      => $dataAdmin['tipe'],
                    'isLogin'   => true,
                ];
                $session->set($ses_data);
                return redirect()->to(base_url('admin'));
            } else {
                $session->setFlashdata('error', 'Email dan Password Salah!');
                return redirect()->to(base_url('login'));
            }
        } elseif ($dataDesigner) {
            $pass = $dataDesigner['password'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'id'        => $dataDesigner['id'],
                    'email'     => $dataDesigner['email'],
                    'tipe'      => $dataDesigner['tipe'],
                    'isLogin'   => true,
                ];
                $session->set($ses_data);
                return redirect()->to(base_url('designer'));
            } else {
                $session->setFlashdata('error', 'Email dan Password Salah!');
                return redirect()->to(base_url('login'));
            }
        } elseif ($dataCustomer) {
            $pass = $dataCustomer['password'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'id'        => $dataCustomer['id'],
                    'email'     => $dataCustomer['email'],
                    'tipe'      => $dataCustomer['tipe'],
                    'isLogin'   => true,
                ];
                $session->set($ses_data);
                return redirect()->to(base_url('customer'));
            } else {
                $session->setFlashdata('error', 'Email dan Password Salah!');
                return redirect()->to(base_url('login'));
            }
        } else {
            $session->setFlashdata('error', 'Masukkan data yang benar!');
            return redirect()->to(base_url('login'));
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('login'));
    }
}
