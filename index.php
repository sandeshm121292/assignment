<?php

use Assignment\Application\Application;
use Assignment\Application\RouteFacade;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use League\Route\Router;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $router = new Router();
    $routes = RouteFacade::getRoutes();

    foreach ($routes as [$method, $path, $handler]) {
        $router->map($method, $path, $handler);
    }
    $request = ServerRequestFactory::fromGlobals(
        $_SERVER,
        $_GET,
        $_POST,
        $_COOKIE,
        $_FILES
    );
    $emitter = new SapiStreamEmitter();

    (new Application($router, $request, $emitter))->run();
} catch (Throwable $exception) {
    echo $exception;
    http_response_code(500);
}

