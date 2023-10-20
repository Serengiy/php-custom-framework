<?php

use App\Services\UserService;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Somecode\Framework\Authentication\SessionAuthentication;
use Somecode\Framework\Authentication\SessionAuthInterface;
use Somecode\Framework\Console\Application;
use Somecode\Framework\Console\Commands\MigrateCommand;
use Somecode\Framework\Console\Kernel as ConsoleKernel;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Dbal\ConnectionFactory;
use Somecode\Framework\Event\EventDispatcher;
use Somecode\Framework\Http\Kernel;
use Somecode\Framework\Http\Middleware\ExtractRouteInfo;
use Somecode\Framework\Http\Middleware\RequestHandler;
use Somecode\Framework\Http\Middleware\RequestHandlerInterface;
use Somecode\Framework\Http\Middleware\RouterDispatch;
use Somecode\Framework\Routing\Router;
use Somecode\Framework\Routing\RouterInterface;
use Somecode\Framework\Session\Session;
use Somecode\Framework\Session\SessionInterface;
use Somecode\Framework\Template\TwigFactory;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();

$dotenv->load(dirname(__DIR__).'/.env');

// Application parameters
$basePath = dirname(__DIR__);
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$routes = include BASE_PATH.'/routes/web.php';
$viewsPath = BASE_PATH.'/views';
$databaseUrl = 'pdo-mysql://lemp:lemp@database:3306/lemp?charset=utf8mb4';
// Application service

$container = new Container();

$container->add('base-path', new StringArgument($basePath));

$container->delegate(new ReflectionContainer(true));

$container->add('framework-commands-namespace', new StringArgument('Somecode\\Framework\\Console\\Commands\\'));

$container->add('APP_ENV', $appEnv);

$container->add(RouterInterface::class, Router::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->addShared(EventDispatcher::class);

$container->add(Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
        EventDispatcher::class,
    ]);

//$container->addShared('twig-loader', FilesystemLoader::class)
//    ->addArgument(new StringArgument($viewsPath));
//
//$container->addShared('twig',Environment::class)
//    ->addArgument('twig-loader');

$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath),
        SessionInterface::class,
        SessionAuthInterface::class,
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(Application::class)->addArgument($container);

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument($basePath.'/database/migrations'));

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container,
    ]);

$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArguments([
        UserService::class,
        SessionInterface::class,
    ]);

$container->add(ExtractRouteInfo::class)
    ->addArgument(new ArrayArgument($routes));

return $container;
