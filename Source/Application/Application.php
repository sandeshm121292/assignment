<?php declare(strict_types=1);

namespace Assignment\Application;

use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;

class Application
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @param Router $router
     * @param ServerRequestInterface $request
     * @param EmitterInterface $emitter
     */
    public function __construct(
        Router                 $router,
        ServerRequestInterface $request,
        EmitterInterface       $emitter
    )
    {
        $this->router = $router;
        $this->request = $request;
        $this->emitter = $emitter;
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function run(): void
    {
        $response = $this->router->dispatch($this->request);

        $this->emitter->emit($response);
    }
}
