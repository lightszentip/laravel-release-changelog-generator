<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;

class ShowVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:show-version {--f|format=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show current version';

    /**
     * Create a new command instance.
     *
     * Initializes the ShowVersion command by calling the parent constructor
     * to set up the base Command functionality.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command to display the current version.
     *
     * Retrieves the current version using the release changelog version service
     * and displays it in the specified format. If no format is provided or the
     * format is empty, uses the default format from Constants. Handles any
     * exceptions that may occur during version retrieval and displays appropriate
     * error messages.
     *
     * @return int Returns Command::SUCCESS (0) on successful execution,
     *             or Command::INVALID (2) if an exception occurs during processing
     */
    public function handle(): int
    {
        try {
            $format = $this->option('format');
            if (null === $format || '' === trim($format)) {
                $format = Constants::DEFAULT_FORMAT;
            }
            $result = app('releasechangelog.version')->showVersion($format);
            $this->info($result);

            return self::SUCCESS;
        } catch (\Exception $e2) {
            $this->error("Error:  {$e2} ");

            return self::INVALID;
        }
    }
}
