<?php

namespace Somecode\Framework\Http\Middleware;

use Somecode\Framework\Http\Request;
use Somecode\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
