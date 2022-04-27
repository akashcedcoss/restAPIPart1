<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Mvc\Micro;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Api\Handlers;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH);
require("./vendor/autoload.php");


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
        'Api\Handlers' =>  '../app/handlers'
    ]
);
$loader->register();

$prod = new Api\Handlers\Product();
$order = new Api\Handlers\Order();
$container = new FactoryDefault();
$app = new Micro($container);

$container->set('view', function () {
    $view = new \Phalcon\Mvc\View\Simple();
    $view->setViewsDir('../../frontend/view');
    return $view;
}, true);


$app->get('/', function () use ($app) {
    // other logic
    echo $app['view']->render('index', ['key' => 'value']);
});
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
            $token = $app->request->getQuery("token");
            $decoded = Firebase\JWT\JWT::decode($token, new Firebase\JWT\Key($key, 'HS256'));
            if (!$decoded->role == "admin") {
                echo "Token not found";
                return false;
            } else {
                return true;
            }
        }
    );
}
$app->get(
    '/app/product/search/{keyword}',
    [
        $prod,
        'search'
    ]
);
$app->get(
    '/app/products/get',
    [
        $prod,
        'getproduct'
    ]
);
$app->get(
    '/app/product/limit/{number}',
    [
        $prod,
        'limit'
    ]
);

$app->get(
    '/app/authorize',
    [
        $prod,
        'jwt'
    ]
);

$app->get(
    '/app/order/create',
    [
        $order,
        'order'
    ]
);

$app->Post(
    '/app/order/update',
    [
        $order,
        'update'
    ]
);

$app->handle(
    $_SERVER['REQUEST_URI']
);
