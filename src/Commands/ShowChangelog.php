<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

/** @psalm-api */
class ShowChangelog extends BaseCommand
{
    protected $signature = 'changelog:show {--ver= : Specific version to show} {--unreleased : Show unreleased items (default)}';

    protected $description = 'Show changelog entries for a version or unreleased';

    private const array META_KEYS = ['name', 'date', 'release', 'modules'];

    public function handle(): int
    {
        try {
            $decoded_json = json_decode(File::get(FileHandler::pathChangelog()), true);

            if (!is_array($decoded_json)) {
                $msg = 'No changelog file found or invalid format';

                return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
            }

            $version = $this->option('ver');

            if ($version) {
                return $this->showVersion($decoded_json, (string) $version);
            }

            return $this->showUnreleased($decoded_json);
        } catch (\Exception $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $changelog
     */
    private function showVersion(array $changelog, string $version): int
    {
        if (!array_key_exists($version, $changelog)) {
            $msg = "Version {$version} not found in changelog";

            return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
        }

        /** @var array<string, mixed> $data */
        $data = $changelog[$version];

        if ($this->isJson()) {
            return $this->outputJson($data);
        }

        $this->info("Version: {$version} — {$data['name']} ({$data['date']})");
        $this->renderEntries($data);

        return self::SUCCESS;
    }

    /**
     * @param array<string, mixed> $changelog
     */
    private function showUnreleased(array $changelog): int
    {
        if (!array_key_exists('unreleased', $changelog)) {
            $msg = 'No unreleased section found in changelog';

            return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
        }

        /** @var array<string, mixed> $data */
        $data = $changelog['unreleased'];

        if ($this->isJson()) {
            return $this->outputJson($data);
        }

        $this->info('Unreleased changes:');
        $this->renderEntries($data);

        return self::SUCCESS;
    }

    /** @param array<string, mixed> $data */
    private function renderEntries(array $data): void
    {
        foreach ($data as $key => $value) {
            if (in_array($key, self::META_KEYS, true) || !is_array($value)) {
                continue;
            }
            $this->line("<comment>{$key}:</comment>");
            foreach ($value as $entry) {
                if (is_array($entry) && isset($entry['message'])) {
                    $issue = isset($entry['issue']) ? " (#{$entry['issue']})" : '';
                    $this->line("  - {$entry['message']}{$issue}");
                }
            }
        }
    }
}
