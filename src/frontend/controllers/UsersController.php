<?php

// require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use MongoDB\BSON\ObjectId;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class UsersController extends Controller
{
    
    public function indexAction()
    {
        
    }
    /**
     * Implemented token at the time user is doing signup on page. Also updated the data in database.
     *
     * @return void
     */
    public function usersAction()
    {
        $name = $this->request->getPost('name');
        $key = "example_key";
        $now        = Date("Y-m-d");
        $date_create = date_create();
        $issued     = date_timestamp_get($date_create);
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => $issued,
            "role" => "admin",
            "name" => $name,
        );
        $token = JWT::encode($payload, $key, 'HS256');
        echo $token;
        $data = [
            "name" => $this->request->getPost('name'),
            "email" => $this->request->getPost('email'),
            "password" => $this->request->getPost('password'),
            "role" => "user",
            "token" => $token
 
         ];
         $collection = $this->mongo->users;
         $res = $collection->insertOne($data);
         die;
    }
}
