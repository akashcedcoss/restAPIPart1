<?php

// require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectId;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class ApiController extends Controller
{

    public function indexAction()
    {
        $data = $this->mongo->products->find();
        $this->view->data = $data;
    }
    public function orderAction()
    {
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJpYXQiOjE2NTA5NTk3NzcsInJvbGUiOiJhZG1pbiIsIm5hbWUiOiJBa2FzaCJ9.x_dLJpWexQBPJ_5g7w6nrkLZgFX2oUNqRsLFnO0ovGs";
        
        if ($this->request->getPost()) {
            $data = array(
                'name' => $this->request->getPost('name'),
                'price' => $this->request->getPost('price'),
                'quantity' => $this->request->getPost('quantity')
            );
            $url = 'http://192.168.2.9:8080//api/order/create?token=' . $token;
            $client = new Client();
            $client->request('POST', $url, ['form_params' => $data]);
            $this->response->redirect("/api");
        }
    }
    public function createOrderAction()
    {
        
        $id = $this->request->getPost('hidden');
        $data = $this->mongo->products->find(["_id" => new MongoDB\BSON\ObjectID($id)]);
        $this->view->data = $data;
    }
}
