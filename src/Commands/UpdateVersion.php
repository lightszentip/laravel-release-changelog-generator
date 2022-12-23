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
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UpdateVersion extends Command
{

    private static string $ar_type = 'type';


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
            $type = trim($this->getArgument(UpdateVersion::$ar_type));

            VersionUtil::updateVersionByType($type);

            $this->info("Current Version: ".app('releasechangelog.version')->showVersion(Constants::DEFAULT_FORMAT));

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
