<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ReleaseChangelog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:release {--rn|releasename= : Name of release} {--t|type=patch : Which update the current version - patch, minor, major, rc, timestamp}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Release version in file';
    private static string $ar_name = 'releasename';

    private static string $ar_type = 'type';

    /**
     * Create a new command instance.
     *
     * Initializes the ReleaseChangelog command by calling the parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command to create a new release version.
     *
     * This method processes the changelog file, validates the release type,
     * updates the version according to the specified type, and generates
     * a new changelog entry with the provided release name.
     *
     * @return int Command exit status - SUCCESS (0), FAILURE (1), or INVALID (2)
     */
    public function handle(): int
    {
        if (!file_exists($this->path())) {
            File::put($this->path(), '');
        }

        try {
            $type = trim($this->getArgument(self::$ar_type));
            $name = trim($this->getArgument(self::$ar_name));

            if ('rc' !== $type && 'patch' !== $type && 'minor' !== $type && 'major' !== $type && 'timestamp' !== $type) {
                $this->error('Please use timestamp,rc, patch, minor or major for a release');

                return CommandAlias::FAILURE;
            }

            $jsonString = file_get_contents($this->path());
            $decoded_json = json_decode($jsonString);
            if (null === $decoded_json || !property_exists($decoded_json, 'unreleased')) {
                $this->error('No release changelog exists to update');

                return CommandAlias::FAILURE;
            }

            VersionUtil::updateVersionByType($type);
            $decoded_json = VersionUtil::generateChangelogWithNewVersion($decoded_json, $name);
            file_put_contents(FileHandler::pathChangelog(), json_encode($decoded_json));

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            return self::FAILURE;
        } catch (\Exception $e2) {
            $this->error("Error:  {$e2} ");

            return self::INVALID;
        }
    }

    /**
     * Get the full path to the changelog JSON file.
     *
     * Constructs the path using the configured release changelog path
     * and appends the .changes directory and changelog.json filename.
     *
     * @return string The absolute path to the changelog.json file
     */
    private function path(): string
    {
        return Config::get('releasechangelog.path').\DIRECTORY_SEPARATOR.'.changes'.\DIRECTORY_SEPARATOR.'changelog.json';
    }

    /**
     * Get an argument value from command options or user input.
     *
     * First attempts to retrieve the value from command line options.
     * If not provided, prompts the user for input. Throws an exception
     * if no value is provided after prompting.
     *
     * @param string $key The argument key to retrieve (e.g., 'releasename', 'type')
     * @return string The argument value provided by the user
     * @throws \InvalidArgumentException When no input is provided for the required key
     */
    private function getArgument(string $key): string
    {
        $result = $this->option($key);

        if (null === $result) {
            $result = $this->ask('What is '.$key.' ?');
        }

        if (null === $result) {
            $this->error("No input for key:  {$key} ");

            throw new \InvalidArgumentException();
        }

        return $result;
    }
}
