<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Rajasa Finishing',
        ];

        return view('customer/index', $data);
    }
}
