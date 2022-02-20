<?php declare(strict_types=1);

namespace Assignment\Test\Helper;

use Assignment\Application\Application;
use Assignment\Application\RouteFacade;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Laminas\Diactoros\ServerRequest;

trait ApiTestHelper
{

    /**
     * @param string $uri
     * @param string $body
     * @param array $headers
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    protected function dispatchPost(
        string $uri,
        string $body,
        array  $headers = []
    ): ResponseInterface
    {
        $stream = fopen('php://temp', 'rb+');
        fwrite($stream, $body);
        rewind($stream);

        return $this->dispatch('POST', $uri, [], $headers, $stream);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $urlParameters
     * @param array $headers
     * @param string|resource $body
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    protected function dispatch(
        string $method,
        string $uri,
        array  $urlParameters = [],
        array  $headers = [],
               $body = 'php://input'
    ): ResponseInterface
    {
        $request = new ServerRequest([], [], $uri, $method, $body, $headers);

        return $this->runApplication(
            $request->withQueryParams($urlParameters)
        );
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    private function runApplication(ServerRequestInterface $request): ResponseInterface
    {
        $emitter = new ApiTestEmitter();

        $application = new Application(
            $this->createRouter(),
            $request,
            $emitter
        );
        $application->run();

        return $emitter->getResponse();
    }

    /**
     * @return Router
     */
    private function createRouter(): Router
    {
        $router = new Router();
        $routes = RouteFacade::getRoutes();
        foreach ($routes as [$method, $path, $handler]) {
            $router->map($method, $path, $handler);
        }
        return $router;
    }
}
