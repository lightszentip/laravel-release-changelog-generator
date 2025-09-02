<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Dto\ChangelogItem;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class AddChangelog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:add {--type= : Type of change} {--i|issue=} {--m|module=} {--message= : Changelog Message} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new item to changelog';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $issue = $this->getArgument('issue', true);
            $module = $this->getArgument('module', true);
            $type = trim($this->getArgument('type'));
            $message = trim($this->getArgument('message'));

            $changelogItem = new ChangelogItem($message, $issue);
            $jsonString = File::get(FileHandler::pathChangelog());
            $decoded_json = json_decode($jsonString);

            // If changelog is empty or does not have 'unreleased', create initial structure
            if (null === $decoded_json || !property_exists($decoded_json, 'unreleased')) {
                $decoded_json = $this->createInitialChangelog($type, $module, $changelogItem);
            } else {
                // Add item to existing changelog structure
                $this->addChangelogItem($decoded_json, $type, $module, $changelogItem);
            }

            File::put(FileHandler::pathChangelog(), json_encode($decoded_json));
            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            $this->error("Error:  {$e} ");
            return self::FAILURE;
        } catch (\Exception $e2) {
            $this->error("Error:  {$e2} ");
            return self::INVALID;
        }
    }

    /**
     * Create the initial changelog structure if it does not exist yet.
     */
    private function createInitialChangelog(string $type, string $module, ChangelogItem $item): object
    {
        $content = ['name' => 'tbd', 'date' => '', 'release' => false, $type => []];
        if ($module) {
            $content['modules'] = [
                [
                    'name' => $module,
                    $type => [$item->build()]
                ]
            ];
        } else {
            $content[$type][] = $item->build();
        }
        $obj = new \stdClass();
        $obj->unreleased = $content;
        return $obj;
    }

    /**
     * Add a changelog item to the existing changelog structure.
     */
    private function addChangelogItem(object &$decoded_json, string $type, string $module, ChangelogItem $item): void
    {
        if (!property_exists($decoded_json->unreleased, $type)) {
            $decoded_json->unreleased->{$type} = [];
        }

        if ($module) {
            if (!property_exists($decoded_json->unreleased, 'modules')) {
                $decoded_json->unreleased->modules = [];
            }

            $modules = $decoded_json->unreleased->modules;
            $found = false;

            foreach ($modules as &$mod) {
                if (is_object($mod)) {
                    $mod = (array) $mod; // Convert stdClass to array
                }
                if (array_key_exists('name', $mod) && $mod['name'] === $module) {
                    if (!isset($mod[$type]) || !is_array($mod[$type])) {
                        $mod[$type] = [];
                    }
                    $mod[$type][] = $item->build();
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $modules[] = [
                    'name' => $module,
                    $type => [$item->build()]
                ];
            }

            $decoded_json->unreleased->modules = $modules;
        } else {
            $decoded_json->unreleased->{$type}[] = $item->build();
        }
    }

    /**
     * Get a command argument, optionally asking the user if not provided.
     */
    private function getArgument(string $key, bool $optional = false): string
    {
        $result = $this->option($key);
        if (!$optional && null === $result) {
            $result = $this->ask('What is '.$key.' ?');
        }
        if (null === $result && $optional) {
            return '';
        }
        if (null === $result && !$optional) {
            $this->error("No input for key:  {$key} ");
            throw new \InvalidArgumentException($this->option($key));
        }

        return $result;
    }
}
