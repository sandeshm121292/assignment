<?php declare(strict_types=1);

namespace Assignment\Application;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use League\Route\Router;

final class ApplicationFactory
{

    /**
     * @return Application
     */
    public function createApplication(): Application
    {
        $router = $this->createRouter();
        $request = $this->createRequest();
        $emitter = $this->createEmitter();

        return new Application($router, $request, $emitter);
    }

    /**
     * @return Router
     */
    private function createRouter(): Router
    {
        return (new RouterFactory())->createRouter();
    }

    /**
     * @return ServerRequest
     */
    private function createRequest(): ServerRequest
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }

    /**
     * @return SapiStreamEmitter
     */
    private function createEmitter(): SapiStreamEmitter
    {
        return new SapiStreamEmitter();
    }
}
