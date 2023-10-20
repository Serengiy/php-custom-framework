<?php

namespace Somecode\Framework\Console;

use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    ) {
    }

    public function handle(): int
    {
        $this->registerCommands();
        $this->application->run();

        return 0;
    }

    private function registerCommands(): void
    {
        $nameSpace = $this->container->get('framework-commands-namespace');
        $commandFiles = new \DirectoryIterator(__DIR__.'/Commands');

        foreach ($commandFiles as $commandFile) {
            if (! $commandFile->isFile()) {
                continue;
            }
            $command = $nameSpace.pathinfo($commandFile, PATHINFO_FILENAME);

            if (is_subclass_of($command, CommandInterface::class)) {

                $name = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();

                //                Добавить Интерфейс в айдишник видeо CLI  Регистрация комманд
                $this->container->add("console:$name", $command);

            }
        }
    }
}
