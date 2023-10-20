<?php

namespace Somecode\Framework\Authentication;

interface SessionAuthInterface
{
    public function authenticate(string $email, string $password): bool;

    public function login(AuthUserInterface $user);

    public function logout();

    public function getUser(): AuthUserInterface;

    public function isLoggedIn(): bool;
}
