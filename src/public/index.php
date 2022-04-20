<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Mvc\Micro;



$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require(BASE_PATH . "/vendor/autoload.php");


// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader = new Loader();


$loader->registerNamespaces(
    [
        'Api\Handlers' => APP_PATH . '/handlers'
    ]
);
$loader->register();

$prod = new Api\Handlers\Product();
$container = new FactoryDefault();
$app = new Micro($container);

$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));
        return $mongo->demo;
    },
    true
);
$cont = explode('/', $app->request->get('_url'));
$cls = str_replace("/", "", $cont[1]);
if ($cls == "products") {
    $app->before(
        function () use ($app) {
            $key = "example_key";
            $token = $app->request->getHeader("Bearer");
            $decoded = Firebase\JWT\JWT::decode($token, new Firebase\JWT\Key($key, 'HS256'));
            if (!$decoded->user_id) {
                echo "Token not found";
                return false;
            } else {
                return true;
            }
        }
    );
}
$app->get(
    '/product/search/{keyword}',
    [
        $prod,
        'search'
    ]
);
$app->get(
    '/products/get',
    [
        $prod,
        'getproduct'
    ]
);
$app->get(
    '/product/limit/{number}',
    [
        $prod,
        'limit'
    ]
);

$app->get(
    '/authorize',
    [
        $prod,
        'jwt'
    ]
);



$app->handle(
    $_SERVER['REQUEST_URI']
);
