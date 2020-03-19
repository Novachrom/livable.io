<?php

namespace App\Utils;

interface OutputInterface
{
    public function write(string $message): void;
}
