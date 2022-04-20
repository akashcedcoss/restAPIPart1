<?php

namespace Multiple\Admin\Controllers;

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $postData = $this->request->getPost();
            print_r($this->escaper($postData['name']));
            die;
            $user = $this->mongo->users->findOne(['name'=>$postData['name'], 'email'=>$postData['email']]);
            if (count($user)>0) {
                $this->response->redirect('/products/dashboard');
            } else {
                $this->response->redirect('/login');
            }
            // print_r($this->request->getPost());
            // die;
        }
    }
}
