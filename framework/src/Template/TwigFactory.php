<?php

namespace Somecode\Framework\Template;

use Somecode\Framework\Authentication\SessionAuthInterface;
use Somecode\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private readonly string $viewPath,
        private readonly SessionInterface $session,
        private readonly SessionAuthInterface $auth,
    ) {
    }

    public function create(): Environment
    {
        $loader = new FilesystemLoader($this->viewPath);
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));
        $twig->addFunction(new TwigFunction('auth', [$this, 'getAuth']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function getAuth(): SessionAuthInterface
    {
        return $this->auth;
    }
}
