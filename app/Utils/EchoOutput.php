<?php

namespace App\Utils;

class EchoOutput implements OutputInterface
{
    public function write(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
