<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Product extends Injectable {
    function get($select = "", $limit = 10, $page = 1) {
        $products = array(
            array("select"=>$select,"where"=>$where,"limit"=>$limit,"page"=>$page),
            array("name"=>"Product 2", "price"=>40),
        );
        return json_encode($products);

    }
    function getproduct()
    {
        $collection = $this->mongo->products;
        $ans = $collection->find();
        foreach ($ans as $val) {
            echo "<pre>";
            print_r($val);
        }
        die;
    }
    function search($keyword)
    {
        if (strpos($keyword, "%20") == true) {
            $newstr = explode("%20", $keyword);
            foreach ($newstr as $str) {
                $strarr[] = array('$or' => array(array("name" => array('$regex' => $str)), array("variations[0].name" => array('$regex' => $str))));
            }
            $products = $this->mongo->products->find(['$or' => $strarr]);
        } else {
            $products = $this->mongo->products->find(['name' => array('$regex' => $keyword)]);
        }
        echo "<pre>";
        foreach ($products as $product) {
            print_r($product);
        }
        
        die;
    }
    function limit($number)
    {
        $no = (int)$number;
        $collection = $this->mongo->products;
        $ans = $collection->find([], ['limit' => $no]);
        foreach ($ans as $val) {
            echo "<pre>";
            print_r($val);
        }
        die;
    }
    function jwt()
    {
        $key = "example_key";
        $now        = Date("Y-m-d");
        $date_create = date_create();
        $issued     = date_timestamp_get($date_create);
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => $issued,
            "user_id" => "420",
        );
        $token = JWT::encode($payload, $key, 'HS256');
        echo $token;
        die;
    }
    
}
