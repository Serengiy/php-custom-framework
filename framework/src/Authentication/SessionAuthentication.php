<?php

namespace Somecode\Framework\Authentication;

use Somecode\Framework\Session\Session;
use Somecode\Framework\Session\SessionInterface;

class SessionAuthentication implements SessionAuthInterface
{
    private AuthUserInterface $user;

    public function __construct(
        private UserServiceInterface $userService,
        private SessionInterface $session,
    ) {
    }

    public function authenticate(string $email, string $password): bool
    {
        $user = $this->userService->findByEmail($email);

        if (! $user) {
            return false;
        }

        if (password_verify($password, $user->getPassword())) {
            $this->login($user);

            return true;
        }

        return false;
    }

    public function login(AuthUserInterface $user)
    {
        $this->session->set(Session::AUTH_KEY, $user->getId());

        $this->user = $user;
    }

    public function logout()
    {
        $this->session->remove(Session::AUTH_KEY);
    }

    public function getUser(): AuthUserInterface
    {
        return $this->user;
    }

    public function isLoggedIn(): bool
    {
        return $this->session->has(Session::AUTH_KEY);
    }
}