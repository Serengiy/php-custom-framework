<?php

use Doctrine\DBAL\Schema\Schema;

return new class
{
    public function up(Schema $schema): void
    {
        echo get_class($this).'method up'.PHP_EOL;
    }

    public function down(Schema $schema): void
    {
        echo get_class($this).'method down'.PHP_EOL;
    }
};
