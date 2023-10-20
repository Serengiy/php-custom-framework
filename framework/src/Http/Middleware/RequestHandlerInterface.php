<?php

namespace Somecode\Framework\Http\Middleware;

use Somecode\Framework\Http\Request;
use Somecode\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;

    public function injectMiddleWare(array $middleware): void;
}
