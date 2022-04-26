<?php

// require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectId;


class IndexController extends Controller
{
    
    public function indexAction()
    {
        // $collection = $this->client->demo->products;
        // $result = $collection->find();
        // foreach ($result as $entry) {
        //     echo $entry['_id'], ': ', $entry['name'], "\n";
        // }
    }
    public function addproductAction()
    {
        echo "<pre>";
        $arr = $this->request->getPost();
        print_r($arr);
        // die;
        $main = [
            'Name' => $this->request->getPost('Name'),
            'Category' => $this->request->getPost('Category'),
            'Stock' => $this->request->getPost('Price'),
            'Price' => $this->request->getPost('Stock'),

        ];
        $variations = [
            'Key' => $this->request->getPost('key'),
            'Val' => $this->request->getPost('val')
        ];
        $additional = [
           'Label' => $this->request->getPost('label'),
           'Value' => $this->request->getPost('value')
        ];
        if ($variations['Key'] && $variations['Val']) {
            array_push($main, $variations);
        }
        if ($additional['Label'] && $additional['Value']) {
            array_push($main, $additional);
        }
        $collection = $this->client->demo->products;
        $result = $collection->insertOne($main);
        $this->response->redirect('index');
    }
    public function listproductsAction()
    {
        $collection = $this->client->demo->products;
        $results = $collection->find();
        $this->view->results = $results;
    }
    public function editAction()
    {
        $id = $this->request->getQuery('id');
        $collection = $this->client->demo->products;
        $id = $this->request->getQuery('id');
        $results = $collection->find(["_id" => new MongoDB\BSON\ObjectID($id)]);
        $this->view->results = $results;
        $this->view->id = $id;
    
    }

    public function editdetailsAction()
    {
        
        $arr = $this->request->getPost('');
        echo "<pre>";
        print_r($arr);
        $collection = $this->client->demo->products;
        $id = $this->request->getPost('id');
        $main = [
            'Name' => $this->request->getPost('Name'),
            'Category' => $this->request->getPost('Category'),
            'Stock' => $this->request->getPost('Price'),
            'Price' => $this->request->getPost('Stock'),

        ];
        $variations = [
            'Key' => $this->request->getPost('Key'),
            'Val' => $this->request->getPost('Val')
        ];
        $additional = [
           'Label' => $this->request->getPost('Label'),
           'Value' => $this->request->getPost('Value')
        ];
        array_push($main, $variations);
        array_push($main, $additional);
        
        $result = $collection->updateOne(["_id"=> new ObjectID($id)], ['$set'=>$main]);
        $this->response->redirect('index/listproducts');


    }
    public function deleteAction()
    {
        $collection = $this->client->demo->products;
        $id = $this->request->getQuery('id');
        $result = $collection->deleteOne(["_id" => new MongoDB\BSON\ObjectID($id)]);
        $this->response->redirect('index/listproducts');
    }
    public function oneproductAction()
    {
        $arr = $this->request->getPost();
        $id = $this->request->getPost('product_id');
        $collection = $this->client->demo->products;
        $oneproduct = $collection->findOne(['_id' => new ObjectID($id)]);
        $data = json_encode($oneproduct);
        print_r($data);
        return $data;
    }
}
