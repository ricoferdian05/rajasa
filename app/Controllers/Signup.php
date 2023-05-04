<?php

namespace App\Controllers;

class Signup extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Sign Up Page',
        ];

        return view('signup', $data);
    }

    public function auth()
    {
        $customer   = new \App\Models\CustomerModel();

        $session = session();

        $email      = $this->request->getVar('email');
        $password   = $this->request->getVar('password');
        $username   = $this->request->getVar('username');
        $name       = $this->request->getVar('name');

        // Check space
        $arrPassword = explode(' ', $password);
        $arrUsername = explode(' ', $username);
        $arrName     = explode(' ', $name);

        // Check email
        $dataCustomer   = $customer->where('email', $email)->first();
        if ($dataCustomer) {
            $session->setFlashdata('error', 'Email already taken!');
            return redirect()->to(base_url('signup'));
        } else {
            if ($arrPassword[0] === '' || $arrUsername[0] === '' || $arrName[0] === '') {
                $session->setFlashdata('error', 'Please input all fields!');
                return redirect()->to(base_url('signup'));
            } else {
                // Generate Id
                $typeAccount = 'cust';
                $arrayName = explode(' ', $name);
                $firstName = $arrayName[0];
                $id = uniqid($typeAccount . '-' . $firstName . '-', true);

                // Generate Password
                $generatePassword = password_hash($password, PASSWORD_DEFAULT);

                $customer->save([
                    'id' => $id,
                    'email' => $email,
                    'password' => $generatePassword,
                    'username' => $username,
                    'nama' => $name,
                    'tipe' => 3,
                ]);
                return redirect()->to(base_url('login'));
            }
        }
    }
}
