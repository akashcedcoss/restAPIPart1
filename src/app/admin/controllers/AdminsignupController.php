<?php

namespace Multiple\Admin\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Escaper;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectId;


class AdminsignupController extends Controller{

    public function indexAction()
    {
        
    }

    public function registerAction()
    {
        $escaper = new Escaper();
        $data = [
            'name' => $escaper->escapeHtml($this->request->getPost('name')),
            'email' => $escaper->escapeHtml($this->request->getPost('email')),
            'password' => $escaper->escapeHtml($this->request->getPost('password')),

        ];
        $collection = $this->mongo->users;
        $result = $collection->insertOne($data);
        
    }
}