<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use League\Container\Container;
use Somecode\Framework\Http\Kernel;
use Somecode\Framework\Http\Request;

define('BASE_PATH', dirname(__DIR__));

$request = Request::createFromGlobals();
/** @var Container $container */
$container = require BASE_PATH.'/config/services.php';

require_once BASE_PATH.'/bootstrap/bootstrap.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
