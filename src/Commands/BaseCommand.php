<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    /** @var array<int, mixed> */
    protected array $sharedOptions = [];

    public function __construct()
    {
        parent::__construct();
    }

    #[\Override]
    protected function configure(): void
    {
        parent::configure();
        $this->addOption('json', null, null, 'Output as JSON');
    }

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

    /** @param array<string, mixed> $data */
    protected function outputJson(array $data): int
    {
        $this->line(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return self::SUCCESS;
    }

    protected function errorJson(string $message): int
    {
        $this->line(json_encode(['error' => $message], JSON_UNESCAPED_UNICODE));

        return self::FAILURE;
    }

    protected function isJson(): bool
    {
        return (bool) $this->option('json');
    }

    protected function failure(string $message): int
    {
        $this->error($message);

        return self::FAILURE;
    }
}
