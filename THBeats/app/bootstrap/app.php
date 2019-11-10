<?php

require_once '../app/config.php';

use App\Controllers\AdminController;
use App\Controllers\ConfirmController;
use App\Controllers\HomeController;
use App\Controllers\ServiceController;
use App\Controllers\SessionStorage;
use App\Controllers\CartController;

require '../vendor/autoload.php';

//App Settings
$config['displayErrorDetails'] = true;
$config['db'] = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'THBeats',
    'username' => 'miahou28',
    'password' => 'rell2882',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];

$app = new \Slim\App(['settings' => $config]);

//Container
$container = $app->getContainer();


//Twig Setup
$container['view'] = function($container){
    $view = new \Slim\Views\Twig('../views/', [
        'cache' => false
    ]);

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

    $view->getEnvironment()->addGlobal('storage', $container->get('SessionStorage'));

    return $view;
};

//Eloquent Setup
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

//Flash Messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

//Home Controller
$container['HomeController'] = function($c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    $songs = $c->get('db')->table('songs');
    $messages = $c->get('db')->table('messages');
    $flash = $c->get('flash');
    return new HomeController($view, $songs, $messages, $flash);
};

//Admin Controller
$container['AdminController'] = function($c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    $songs = $c->get('db')->table('songs');
    $kits = $c->get('db')->table('kits');
    $messages = $c->get('db')->table('messages');
    $services = $c->get('db')->table('service');
    $orders = $c->get('db')->table('orders');
    $flash = $c->get('flash');
    return new AdminController($view, $songs, $kits, $flash, $messages, $services, $orders);
};

//Service Controller
$container['ServiceController'] = function($c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    $flash = $c->get('flash');
    $service = $c->get('db')->table('service');
    return new ServiceController($view, $flash, $service);
};

$container['SessionStorage'] = function($c) {
    return new SessionStorage();
};

$container['CartController'] = function($c) {
    $view = $c->get('view');
    $songs = $c->get('db')->table('songs');
    return new CartController($view ,$songs);
};

$container['ConfirmController'] = function($c) {
    $view = $c->get('view');
    $songs = $c->get('db')->table('songs');
    $orders = $c->get('db')->table('orders');
    return new ConfirmController($view ,$songs, $orders);
};

require 'routes.php';

$app->run();