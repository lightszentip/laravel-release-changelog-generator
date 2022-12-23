<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Exception;
use http\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Dto\ChangelogItem;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Symfony\Component\Console\Command\Command as CommandAlias;

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
            $result=  app('releasechangelog.version')->showVersion($format);
            $this->info($result);
            return self::SUCCESS;
        } catch (\Exception $e2) {
            $this->error("Error:  $e2 ");
            return self::INVALID;
        }
    }
}
