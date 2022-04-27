<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MongoDB\BSON\ObjectId;
use GuzzleHttp\Client;

class Order extends Injectable
{
    function order()
    {
        $token = $this->request->getHeader('token');
        $key = "example_key";
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        print_r($decoded->name);
        $user_id = $this->mongo->users->find(["name" => $decoded->name]);
        foreach ($user_id as $val) {
            $userid = $val['_id'];
            $username = $val['name'];
        }
        
        $name = $this->request->getHeader('name');
        if ($name) {
            $data = array(
                "userid" => $userid,
                "username" => $username,
                "name" => $this->request->getHeader('name'),
                "price" => $this->request->getHeader('price'),
                "quantity" => $this->request->getHeader('quantity'),
                "status" => "pending"
            );

            $collection = $this->mongo->orders;
            $res = $collection->insertOne($data);
            $oid = $this->mongo->orders->findOne(["userid" => $userid]);
// Implemented WebHOoks and creating product
            $data += [
                'orderid'=>$oid['_id'],
            ];

            $url="http://192.168.48.1:8080/admin/createorder";
            $client = new Client([
                'base_uri' => $url,
            ]);
            $response = $client->request('POST', "/admin/createorder", ['form_params'=>$data]);
            $body = $response->getBody()->getContents();
        }
    }
    function update()
    {
        $token = $this->request->getHeader('token');
        $oid = $this->request->getHeader('oid');
        $status = $this->request->getHeader('status');
        $order =  $this->mongo->orders->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectID($oid)],
            ['$set' => ["status" => "$status"]],
        );
        $data = array(
            'status' => $status,
            'id' => $oid
            );
        

        $url="http://192.168.48.1:8080/admin/updateorder";
        $client = new Client([
            'base_uri' => $url,
        ]);
        $response = $client->request('POST', "/admin/updateorder", ['form_params'=>$data]);
        $body = $response->getBody()->getContents();
        print_r($body);
        if ($order) {
            echo "Updated order $oid";
            
        }

        
    }
}
