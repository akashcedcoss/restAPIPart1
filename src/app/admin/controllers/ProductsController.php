<?php

namespace Multiple\Admin\Controllers;

require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectID;


class ProductsController extends Controller {

    public function indexAction()
    {
       
    }
    public function addproductAction()
    {
        $data = $this->request->getPost();
        $collection = $this->mongo->products;
        $result = $collection->insertOne($data);
    }
    public function listproductAction()
    {
        $collection = $this->mongo->products;
        $results = $collection->find();
        $this->view->results = $results;
    }
    public function deleteAction()
    {
        $collection = $this->mongo->products;
        $id = $this->request->getPost('id');
        $result = $collection->deleteOne(["_id" => new ObjectID($id)]);
        print_r($result);
        $this->response->redirect('/products/listproduct');
    }
    public function editAction()
    {
        $id  = $this->request->getPost('id');
        $collection = $this->mongo->products;
        $result = $collection->find(["_id" => new ObjectID($id)]);
        $this->view->results = $result;
        $this->view->id = $id;

    }
    public function editdetailsAction()
    {
        
        $collection = $this->mongo->products;
        $id = $this->request->getPost('id');
        $main = [
            'Name' => $this->request->getPost('Name'),
            'Category' => $this->request->getPost('Category'),
            'Price' => $this->request->getPost('Price'),
            'Stock' => $this->request->getPost('Stock'),

        ];
        
        $result = $collection->updateOne(["_id"=> new ObjectID($id)], ['$set'=>$main]);
        $this->response->redirect('/products/listproduct');


    }
}
