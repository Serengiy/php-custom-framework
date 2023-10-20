<?php

namespace Somecode\Framework\Http\Middleware;

use Somecode\Framework\Authentication\SessionAuthInterface;
use Somecode\Framework\Http\RedirectResponse;
use Somecode\Framework\Http\Request;
use Somecode\Framework\Http\Response;
use Somecode\Framework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();
        if (! $this->auth->isLoggedIn()) {
            $this->session->setFlash('error', 'Yu have to login to get started');

            return new RedirectResponse('/login');
        }

        return $handler->handle($request);
    }
}
