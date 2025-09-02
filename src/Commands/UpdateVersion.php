<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;

class UpdateVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:update-version {--type= : Type of version update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the current version over command line';

    private static string $ar_type = 'type';

    /**
     * Create a new command instance.
     *
     * Initializes the UpdateVersion command by calling the parent constructor
     * to set up the base Command functionality.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $type = trim($this->getArgument(self::$ar_type));

            VersionUtil::updateVersionByType($type);

            $this->info('Current Version: '.app('releasechangelog.version')->showVersion(Constants::DEFAULT_FORMAT));

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
     * Retrieves a command line argument/option value with optional user prompting.
     *
     * This method first attempts to get the value from command line options. If the value
     * is not provided and the parameter is not optional, it will prompt the user for input.
     * For optional parameters, an empty string is returned when no value is provided.
     *
     * @param string $key      The name of the argument/option to retrieve
     * @param bool   $optional Whether the argument is optional (defaults to false)
     *
     * @return string The argument value, empty string for optional missing arguments
     *
     * @throws \InvalidArgumentException When a required argument is not provided
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
