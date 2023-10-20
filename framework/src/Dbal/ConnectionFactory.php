<?php

namespace Somecode\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function __construct(
        private readonly string $DbUrl
    ) {
    }

    public function create(): Connection
    {
        return DriverManager::getConnection([
            'url' => $this->DbUrl,
        ]);
    }
}
