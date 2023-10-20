<?php

namespace Somecode\Framework\Console;

interface CommandInterface
{
    public function execute(array $params = []): int;
}
