<?php

namespace App\Controllers;

use App\Services\UserService;
use Somecode\Framework\Authentication\SessionAuthInterface;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\RedirectResponse;

class LoginController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private SessionAuthInterface $auth
    ) {
    }

    public function form()
    {
        return $this->render('login.html.twig');
    }

    public function login(): RedirectResponse
    {
        $isAuth = $this->auth->authenticate(
            $this->request->input('email'),
            $this->request->input('password')
        );
        if (! $isAuth) {
            $this->request->getSession()->setFlash('error', 'Wrong credentials');

            return new RedirectResponse('/login');
        }
        $this->request->getSession()->setFlash('success', 'Logged in successfully');

        return new RedirectResponse('/dashboard');
    }

    public function logout(): RedirectResponse
    {
        $this->auth->logout();

        return new RedirectResponse('/login');
    }
}
