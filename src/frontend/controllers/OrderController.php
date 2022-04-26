<?php

require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectId;


class OrderController extends Controller
{
    public function indexAction()
    {
        //
    }
    public function orderAction()
    {
        $collection = $this->client->demo->products;
        $data = $collection->find();
        $this->view->data = $data;
    }
    public function orderplaceAction()
    {
        $arr = $this->request->getPost();

        $main = [
            'product_name' => $this->request->getPost('product'),
            'customer_name' => $this->request->getPost('name'),
            'quantity' => $this->request->getPost('quantity'),
            'status' => 'paid',
            'orderdate' => $this->request->getPost('date'),
        ];
        $collection = $this->client->demo->orders;
        $result = $collection->insertOne($main);
        $this->response->redirect('/order/orderlist');

    }
    public function orderlistAction()
    {
        $collection = $this->client->demo->orders;
        $results = $collection->find();
        $this->view->results = $results;
        if ($this->request->has('custom_date')) {
            $startDate = $this->request->getPost('start_date');
            $endDate = $this->request->getPost('end_date');

            $this->view->results = $this->client->demo->orders->find([
                'orderdate' => ['$gte' => $startDate, '$lte' => $endDate]
            ]);
        }
        if ($this->request->has('today')) {
            $startDate = date("m/d/y");
            $endDate = date("m/d/y");

            $this->view->results = $this->client->demo->orders->find([
                'orderdate' => ['$gte' => $startDate, '$lte' => $endDate]
            ]);
        }
        if ($this->request->has('week')) {
            $startDate = date("m/d/y", "+7 days");
            $endDate = date("m/d/y");

            $this->view->results = $this->client->demo->orders->find([
                'orderdate' => ['$gte' => $startDate, '$lte' => $endDate]
            ]);
        }
        if ($this->request->has('month')) {
            $startDate = date("m/d/y");
            $endDate = date("m/d/y", "-30 days");

            $this->view->results = $this->client->demo->orders->find([
                'orderdate' => ['$gte' => $startDate, '$lte' => $endDate]
            ]);
        }
    }
    public function changestatusAction()
    {
        $collection = $this->client->demo->orders;
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $result = $collection->updateOne(["_id"=> new ObjectID($id)], ['$set'=>array("status"=>"$status")]);
        $this->response->redirect('order/orderlist');
        
    }
    public function currenttimeAction()
    {
        
        // $this->response->redirect('order/orderlist');
    }
}