<?php

// require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectId;


class AdminController extends Controller
{

    public function indexAction()
    {

    }
    /**
     * Checking Admin credential with database and redirecting to dashboard.
     *
     * @return void
     */
    public function loginAction()
    {
        $login = $this->mongo->users->findOne(
            [
                "email" => $this->request->getPost('email'),
                "password" => $this->request->getPost('password'),
            ]
        );
        if ($login) {
            $this->response->redirect('/admin/dashboard');
        } else {
            echo "Wrong Credentials";
        }
    }
    public function dashboardAction()
    {
        $data = $this->mongo->orders->find();
        $this->view->data = $data;
    }
}
