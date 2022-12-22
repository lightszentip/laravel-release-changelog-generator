<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ReleaseChangelog extends Command
{
    private static string $ar_name = 'releasename';

    private static string $ar_type = 'type';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:release {--rn|releasename= : Name of release} {--t|type=patch : Which update the current version - patch, minor, major, rc}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Release version in file';

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
        if (! file_exists($this->path())) {
            File::put($this->path(), '');
        }
        try {
            $type = trim($this->getArgument(ReleaseChangelog::$ar_type));
            $name = trim($this->getArgument(ReleaseChangelog::$ar_name));

            if ($type != 'rc' && $type != 'patch' && $type != 'minor' && $type != 'major') {
                $this->error('Please use rc, patch, minor or major for a release');

                return CommandAlias::FAILURE;
            }

            $jsonString = file_get_contents($this->path());
            $decoded_json = json_decode($jsonString);
            if ($decoded_json == null || ! property_exists($decoded_json, 'unreleased')) {
                $this->error('No release changelog exists to update');

                return CommandAlias::FAILURE;
            } else {
                switch ($type) {
                    case 'patch':
                        app(Constants::APP_VERISON_HANDLING)->incrementPatch();
                        break;
                    case 'minor':
                        app(Constants::APP_VERISON_HANDLING)->incrementMinor();
                        break;
                    case 'major':
                        app(Constants::APP_VERISON_HANDLING)->incrementMajor();
                        break;
                    case 'rc':
                        app(Constants::APP_VERISON_HANDLING)->incrementPreRelease();
                        break;
                }

                $typeFormat = 'changelogversion';

                $version = app(Constants::APP_VERISON)->showVersion($typeFormat);
                $dateNow = new \DateTimeImmutable();
                $decoded_json->$version = $decoded_json->unreleased;
                $decoded_json->$version->name = $name;
                $decoded_json->$version->release = true;
                $decoded_json->$version->date = $dateNow->format('Y-m-d H:i:s');
                $decoded_json->unreleased = ['name' => 'tbd', 'date' => '', 'release' => false];
            }

            file_put_contents(FileHandler::pathChangelog(), json_encode($decoded_json));

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            return self::FAILURE;
        } catch (\Exception $e2) {
            $this->error("Error:  $e2 ");

            return self::INVALID;
        }
    }

    private function path(): string
    {
        return Config::get('releasechangelog.path').DIRECTORY_SEPARATOR.'.changes'.DIRECTORY_SEPARATOR.'changelog.json';
    }

    private function getArgument(string $key): string
    {
        $optional = false;
        $result = $this->option($key);

        if (is_null($result)) {
            $result = $this->ask('What is '.$key.' ?');
        }

        if ($result == null) {
            $this->error("No input for key:  $key ");
            throw new \InvalidArgumentException();
        }

        return $result;
    }
}
