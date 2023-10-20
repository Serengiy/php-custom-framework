<?php

namespace App\Listeners;

use Somecode\Framework\Dbal\Event\EntityPersist;

class HandleEntityListener
{
    public function __invoke(EntityPersist $event): void
    {
    }
}
