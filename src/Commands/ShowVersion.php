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
            $format = $this->option('format');
            if (is_null($format) || trim($format) == "") {
                $format = Constants::DEFAULT_FORMAT;
            }
            $result = app('releasechangelog.version')->showVersion($format);
            $this->info($result);
            return self::SUCCESS;
        } catch (\Exception $e2) {
            $this->error("Error:  $e2 ");
            return self::INVALID;
        }
    }
}
