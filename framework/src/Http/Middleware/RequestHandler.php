<?php

namespace Somecode\Framework\Http\Middleware;

use Psr\Container\ContainerInterface;
use Somecode\Framework\Http\Request;
use Somecode\Framework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleWares = [
        ExtractRouteInfo::class,
        StartSession::class,
        RouterDispatch::class,
    ];

    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleWares)) {
            return new Response('server error', 500);
        }
        $middleWareClass = array_shift($this->middleWares);
        $middleWare = $this->container->get($middleWareClass);

        return $middleWare->process($request, $this);
    }

    public function injectMiddleWare(array $middleware): void
    {
        array_splice($this->middleWares, 0, 0, $middleware);
    }
}
