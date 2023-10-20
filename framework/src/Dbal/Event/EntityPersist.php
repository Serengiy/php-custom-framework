<?php

namespace Somecode\Framework\Dbal\Event;

use Somecode\Framework\Dbal\Entity;
use Somecode\Framework\Event\Event;

class EntityPersist extends Event
{
    public function __construct(
        private Entity $entity
    ) {
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }
}
