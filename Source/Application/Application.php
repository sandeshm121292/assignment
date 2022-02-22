<?php declare(strict_types=1);

namespace Assignment\Application;

use Assignment\Application\Exception\CannotRunApplicationException;
use Exception;
use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;

/**
 * Using simple routing library here, it will be lightweight and serves the purpose for this assignment
 * Thinking of microservices approach here, so we don't require huge frameworks to run simple microservices.
 *
 * Potential improvement: Add middleware support.
 *
 * @link https://route.thephpleague.com/5.x/
 */
final class Application
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
     * @throws CannotRunApplicationException
     */
    public function run(): void
    {
        try {
            $response = $this->router->dispatch($this->request);

            $this->emitter->emit($response);
        } catch (Exception $exception) {
            throw CannotRunApplicationException::create($exception);
        }
    }
}
