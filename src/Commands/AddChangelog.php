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
        $content = ['name' => 'tbd', 'date' => '', 'release' => false];
        if ($module) {
            $content[$type] = [
                'modules' => [
                    [
                        'name' => $module,
                        'items' => [$item->build()]
                    ]
                ]
            ];
        } else {
            $content[$type] = [$item->build()];
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
            if ($module) {
                $decoded_json->unreleased->{$type} = [
                    'modules' => [
                        [
                            'name' => $module,
                            'items' => [$item->build()]
                        ]
                    ]
                ];
            } else {
                $decoded_json->unreleased->{$type} = [$item->build()];
            }
            return;
        }

        // Type already exists
        if ($module) {
            $typeData = $decoded_json->unreleased->{$type};
            // Wenn noch keine Module-Struktur, migriere alte Einträge
            if (is_array($typeData) && !isset($typeData['modules'])) {
                $modules = [];
                foreach ($typeData as $itemWithoutModule) {
                    $modules[] = [
                        'name' => '',
                        'items' => [is_object($itemWithoutModule) ? (array)$itemWithoutModule : $itemWithoutModule]
                    ];
                }
                $typeData = ['modules' => $modules];
                $decoded_json->unreleased->{$type} = $typeData;
            }
            $modules = $this->extractModules($decoded_json->unreleased->{$type});
            $found = false;
            foreach ($modules as &$mod) {
                if ($mod['name'] === $module) {
                    $mod['items'][] = $item->build();
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $modules[] = [
                    'name' => $module,
                    'items' => [$item->build()]
                ];
            }
            $this->storeModules($decoded_json->unreleased, $type, $modules);
        } else {
            // No module, add directly
            if (is_array($decoded_json->unreleased->{$type})) {
                $decoded_json->unreleased->{$type}[] = $item->build();
            } else {
                $decoded_json->unreleased->{$type} = [$item->build()];
            }
        }
    }

    /**
     * Extract the modules array from the type data.
     *
     * This method normalizes different data structures to extract module information.
     * It handles both array and object type data, converting objects to arrays for consistency.
     * If the type data contains items without modules, it wraps them in modules with empty names.
     *
     * @param mixed $typeData The type data which can be an array or object containing modules or direct items
     * @return array An array of module arrays, where each module has 'name' and 'items' keys
     */
    private function extractModules(mixed $typeData): array
    {
        // Always return an array of arrays (not objects)
        if (is_array($typeData) && isset($typeData['modules'])) {
            return array_map(static function($mod) {
                return is_object($mod) ? (array)$mod : $mod;
            }, $typeData['modules']);
        }
        if (is_object($typeData) && isset($typeData->modules)) {
            return array_map(static function($mod) {
                return is_object($mod) ? (array)$mod : $mod;
            }, (array)$typeData->modules);
        }
        // If there are already items without a module, convert them to a module with empty name
        $modules = [];
        if (is_array($typeData)) {
            foreach ($typeData as $item) {
                if (!is_array($item) && !is_object($item)) {
                    continue;
                }
                $modules[] = [
                    'name' => '',
                    'items' => [is_object($item) ? (array)$item : $item]
                ];
            }
        }
        return $modules;
    }

    /**
     * Store the modules array back into the changelog structure.
     *
     * This method updates the unreleased changelog section by storing the provided modules
     * array under the specified type. It handles different data structures (arrays and objects)
     * and ensures the modules are properly stored regardless of the current structure format.
     *
     * @param mixed $unreleased Reference to the unreleased section of the changelog that will be modified
     * @param string $type The changelog type (e.g., 'added', 'changed', 'fixed') where modules should be stored
     * @param array $modules Array of module data to be stored, where each module contains 'name' and 'items' keys
     * @return void
     */
    private function storeModules(&$unreleased, string $type, array $modules): void
    {
        if (is_array($unreleased->{$type})) {
            $unreleased->{$type}['modules'] = $modules;
        } elseif (is_object($unreleased->{$type})) {
            $unreleased->{$type}->modules = $modules;
        } else {
            $unreleased->{$type} = ['modules' => $modules];
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
