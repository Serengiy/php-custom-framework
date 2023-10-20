<?php

namespace Somecode\Framework\Http\Middleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Somecode\Framework\Http\Exceptions\MethodNotAllowedException;
use Somecode\Framework\Http\Exceptions\RouteNotFoundException;
use Somecode\Framework\Http\Request;
use Somecode\Framework\Http\Response;

use function FastRoute\simpleDispatcher;

class ExtractRouteInfo implements MiddlewareInterface
{
    public function __construct(
        private array $routes,
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                //                spread оператор (...$route) разбивает роутер на 'GET', '/', '[0 => "App\Controllers\HomeController" 1 => "index"]'
                $collector->addRoute(...$route);
            }
        });
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                $handler->injectMiddleware($routeInfo[1][1]);
                break;
                //                [$status, $handler, $vars] = $routeInfo;
                //                return [$handler, $vars];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $e = new MethodNotAllowedException("Supported HTTP methods: $allowedMethods");
                $e->setStatusCode(405);
                throw $e;
            default:
                $e = new RouteNotFoundException('Route not found');
                $e->setStatusCode(404);
                throw $e;
        }

        return $handler->handle($request);
    }
}
