<?php

namespace Somecode\Framework\Http;

use League\Container\Container;
use Somecode\Framework\Event\EventDispatcher;
use Somecode\Framework\Http\Events\ResponseEvent;
use Somecode\Framework\Http\Exceptions\HttpException;
use Somecode\Framework\Http\Middleware\RequestHandlerInterface;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private Container $container,
        private RequestHandlerInterface $requestHandler,
        private readonly EventDispatcher $eventDispatcher
    ) {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {

        try {
            $response = $this->requestHandler->handle($request);

        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

        //        call_user_func_array позволяет разбить vars на аргументы и в методы прилетает уже указанный аргумент
        //        ex: in $vars we have id and in our method (ex, index(id)) we can get id key from $vars if it exists
        //        return call_user_func_array([new $controller, $method], $vars);
        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        $request->getSession()?->clearFlash();
    }

    private function createExceptionResponse(\Exception $e)
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server error', 500);
    }
}
