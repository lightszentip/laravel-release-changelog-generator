<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

/** @psalm-api */
class SuggestRelease extends BaseCommand
{
    protected $signature = 'changelog:suggest-release';

    protected $description = 'Suggest a version bump type based on unreleased changelog entries';

    private const array META_KEYS = ['name', 'date', 'release', 'modules'];

    private const array MAJOR_TYPES = ['breaking'];

    private const array MINOR_TYPES = ['feat', 'feature'];

    public function handle(): int
    {
        try {
            $decoded_json = json_decode(File::get(FileHandler::pathChangelog()), true);

            if (!is_array($decoded_json) || !array_key_exists('unreleased', $decoded_json)) {
                $msg = 'No changelog file or unreleased section found';

                return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
            }

            /** @var array<string, mixed> $unreleased */
            $unreleased = $decoded_json['unreleased'];
            [$type, $reason] = $this->determineType($unreleased);

            if ($this->isJson()) {
                return $this->outputJson(['type' => $type, 'reason' => $reason]);
            }

            $this->info("Suggested bump: {$type}");
            $this->line($reason);

            return self::SUCCESS;
        } catch (\Exception $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $unreleased
     * @return array{0: string, 1: string}
     */
    private function determineType(array $unreleased): array
    {
        $types = $this->collectTypes($unreleased);

        foreach (self::MAJOR_TYPES as $major) {
            if (in_array($major, $types, true)) {
                return ['major', "Breaking change type '{$major}' found in unreleased entries"];
            }
        }

        foreach (self::MINOR_TYPES as $minor) {
            if (in_array($minor, $types, true)) {
                return ['minor', "Feature type '{$minor}' found in unreleased entries"];
            }
        }

        if (empty($types)) {
            return ['patch', 'No unreleased entries found; conservative patch bump recommended'];
        }

        return ['patch', 'Only patch-level types ('.implode(', ', $types).') found in unreleased entries'];
    }

    /**
     * @param array<string, mixed> $unreleased
     * @return list<string>
     */
    private function collectTypes(array $unreleased): array
    {
        $types = [];
        foreach ($unreleased as $key => $value) {
            if (!in_array($key, self::META_KEYS, true) && is_array($value) && count($value) > 0) {
                $types[] = $key;
            }
        }

        return $types;
    }
}
