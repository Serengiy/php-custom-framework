<?php

namespace App\Providers;

use App\Listeners\ContentLengthListener;
use App\Listeners\HandleEntityListener;
use App\Listeners\InternalErrorListener;
use Somecode\Framework\Dbal\Event\EntityPersist;
use Somecode\Framework\Event\EventDispatcher;
use Somecode\Framework\Http\Events\ResponseEvent;
use Somecode\Framework\Providers\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLengthListener::class,
        ],
        EntityPersist::class => [
            HandleEntityListener::class,
        ],
    ];

    public function __construct(
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function register(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->eventDispatcher->addListener($event, new $listener);
            }
        }
    }
}
