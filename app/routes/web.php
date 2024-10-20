<?php


use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Controllers\HomeController;

$request = new Request();
$response = new Response();
$router = new Router($request, $response);

$router->get('/', [HomeController::class, "home"]);


$router->exec();
