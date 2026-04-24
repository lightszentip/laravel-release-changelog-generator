<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

/** @psalm-api */
class ListChangelog extends BaseCommand
{
    protected $signature = 'changelog:list';

    protected $description = 'List all released versions';

    private const array META_KEYS = ['name', 'date', 'release', 'modules'];

    public function handle(): int
    {
        try {
            $decoded_json = json_decode(File::get(FileHandler::pathChangelog()), true);

            if (!is_array($decoded_json)) {
                $msg = 'No changelog file found or invalid format';

                return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
            }

            $releases = $this->buildReleaseList($decoded_json);

            if ($this->isJson()) {
                return $this->outputJson($releases);
            }

            $this->table(
                ['Version', 'Name', 'Date', 'Entries'],
                array_map(fn (array $r) => [$r['version'], $r['name'], $r['date'], $r['count']], $releases)
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $changelog
     * @return array<int, array{version: string, name: string, date: string, count: int}>
     */
    private function buildReleaseList(array $changelog): array
    {
        $releases = array_filter($changelog, static fn (string $key) => $key !== 'unreleased', ARRAY_FILTER_USE_KEY);

        uksort($releases, 'version_compare');
        $releases = array_reverse($releases, true);

        $result = [];
        foreach ($releases as $version => $data) {
            if (!is_array($data)) {
                continue;
            }
            $result[] = [
                'version' => (string) $version,
                'name'    => (string) ($data['name'] ?? ''),
                'date'    => (string) ($data['date'] ?? ''),
                'count'   => $this->countEntries($data),
            ];
        }

        return $result;
    }

    /** @param array<string, mixed> $data */
    private function countEntries(array $data): int
    {
        $count = 0;
        foreach ($data as $key => $value) {
            if (!in_array($key, self::META_KEYS, true) && is_array($value)) {
                $count += count($value);
            }
        }

        return $count;
    }
}
