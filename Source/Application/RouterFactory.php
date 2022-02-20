<?php declare(strict_types=1);

namespace Assignment\Application;

use League\Route\Router;

final class RouterFactory
{
    /**
     * @return Router
     */
    public function createRouter(): Router
    {
        $router = new Router();
        $routes = RouteFacade::getRoutes();

        foreach ($routes as [$method, $path, $handler]) {
            $router->map($method, $path, $handler);
        }

        return $router;
    }
}