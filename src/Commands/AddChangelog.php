<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Dto\ChangelogItem;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class AddChangelog extends Command
{
    private static string $ar_message = 'message';

    private static string $ar_type = 'type';

    private static string $ar_issue = 'issue';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:add {--type= : Type of change} {--i|issue=} {--message= : Changelog Message} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new item to changelog';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $issue = $this->getArgument(AddChangelog::$ar_issue, true);
            $type = trim($this->getArgument(AddChangelog::$ar_type));
            $message = trim($this->getArgument(AddChangelog::$ar_message));

            $changelogItem = new ChangelogItem($message, $issue);

            $jsonString = File::get(FileHandler::pathChangelog());
            $decoded_json = json_decode($jsonString);
            if ($decoded_json == null || ! property_exists($decoded_json, 'unreleased')) {
                $content = ['name' => 'tbd', 'date' => '', 'release' => false, $type => [$changelogItem->build()]];
                if ($decoded_json == null) {
                    $decoded_json['unreleased'] = $content;
                } else {
                    $decoded_json->unreleased = $content;
                }
            } else {
                $decoded_json->unreleased->$type[] = $changelogItem->build();
            }

            File::put(FileHandler::pathChangelog(), json_encode($decoded_json));

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            $this->error("Error:  $e ");
            return self::FAILURE;
        } catch (\Exception $e2) {
            $this->error("Error:  $e2 ");
            return self::INVALID;
        }
    }

    private function getArgument(string $key, bool $optional = false): string
    {
        $result = $this->option($key);

        if (!$optional && is_null($result)) {
            $result = $this->ask('What is '.$key.' ?');
        }

        if ($result == null && $optional) {
            return '';
        } elseif ($result == null && ! $optional) {
            $this->error("No input for key:  $key ");
            throw new \InvalidArgumentException($this->option($key));
        }

        return $result;
    }
}
