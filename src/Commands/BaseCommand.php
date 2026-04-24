<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    protected function getArgument(string $key, bool $optional = false): string
    {
        $result = $this->option($key);

        if (!$optional && null === $result) {
            $result = $this->ask('What is '.$key.' ?');
        }

        if (null === $result && $optional) {
            return '';
        }

        if (null === $result) {
            $this->error("No input for key: {$key}");
            throw new \InvalidArgumentException($key);
        }

        return $result;
    }
}
