<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Somecode\Framework\Authentication\SessionAuthInterface;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\RedirectResponse;
use Somecode\Framework\Http\Response;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private SessionAuthInterface $auth,
    ) {
    }

    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register()
    {
        $form = new RegisterForm($this->userService);

        $form->setFields(
            $this->request->input('name'),
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('passwordConfirmation'),
        );

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }

            return new RedirectResponse('/register');
        }
        $user = $form->save();

        $this->request->getSession()->setFlash('success', "{$user->getEmail()} Done!");

        $this->auth->login($user);

        return new RedirectResponse('/dashboard');
    }
}
