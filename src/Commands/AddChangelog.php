<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Dto\ChangelogItem;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

/** @psalm-api */
class AddChangelog extends BaseCommand
{
    protected $signature = 'changelog:add {--type= : Type of change} {--i|issue=} {--m|module=} {--message= : Changelog Message} ';

    protected $description = 'Add new item to changelog';

    public function handle(): int
    {
        try {
            $issue = $this->getArgument('issue', true);
            $module = $this->getArgument('module', true);
            $type = trim($this->getArgument('type'));
            $message = trim($this->getArgument('message'));

            $changelogItem = new ChangelogItem($message, $issue);
            $decoded_json = json_decode(File::get(FileHandler::pathChangelog()), true);

            if (!is_array($decoded_json) || !array_key_exists('unreleased', $decoded_json)) {
                $decoded_json = $this->createInitialChangelog($type, $module, $changelogItem);
            } else {
                $this->addChangelogItem($decoded_json, $type, $module, $changelogItem);
            }

            File::put(FileHandler::pathChangelog(), json_encode($decoded_json));

            if ($this->isJson()) {
                return $this->outputJson(['success' => true, 'type' => $type, 'message' => $message]);
            }

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        } catch (\Exception $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        }
    }

    /** @return array<string, mixed> */
    private function createInitialChangelog(string $type, string $module, ChangelogItem $item): array
    {
        $entry = ['name' => 'tbd', 'date' => '', 'release' => false, $type => []];

        if ($module) {
            $entry['modules'] = [['name' => $module, $type => [$item->build()]]];
        } else {
            $entry[$type][] = $item->build();
        }

        return ['unreleased' => $entry];
    }

    /** @param array<string, mixed> $decoded_json */
    private function addChangelogItem(array &$decoded_json, string $type, string $module, ChangelogItem $item): void
    {
        if (!array_key_exists($type, $decoded_json['unreleased'])) {
            $decoded_json['unreleased'][$type] = [];
        }

        if ($module) {
            if (!array_key_exists('modules', $decoded_json['unreleased'])) {
                $decoded_json['unreleased']['modules'] = [];
            }

            $found = false;
            foreach ($decoded_json['unreleased']['modules'] as &$mod) {
                if (array_key_exists('name', $mod) && $mod['name'] === $module) {
                    if (!isset($mod[$type]) || !is_array($mod[$type])) {
                        $mod[$type] = [];
                    }
                    $mod[$type][] = $item->build();
                    $found = true;
                    break;
                }
            }
            unset($mod);

            if (!$found) {
                $decoded_json['unreleased']['modules'][] = ['name' => $module, $type => [$item->build()]];
            }
        } else {
            $decoded_json['unreleased'][$type][] = $item->build();
        }
    }
}
