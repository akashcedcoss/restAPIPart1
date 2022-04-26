<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MongoDB\BSON\ObjectId;

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
            $data = [
                "userid" => $userid,
                "username" => $username,
                "name" => $this->request->getHeader('name'),
                "price" => $this->request->getHeader('price'),
                "status" => "pending"
            ];

            $collection = $this->mongo->orders;
            $res = $collection->insertOne($data);
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
        if ($order) {
            echo "Updated order $oid";
            die;
        }
    }
}
