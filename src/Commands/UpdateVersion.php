<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;

/** @psalm-api */
class UpdateVersion extends BaseCommand
{
    protected $signature = 'changelog:update-version {--type= : Type of version update}';

    protected $description = 'Update the current version over command line';

    private static string $ar_type = 'type';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $type = trim($this->getArgument(self::$ar_type));

            VersionUtil::updateVersionByType($type);

            $this->info('Current Version: '.app('releasechangelog.version')->showVersion(Constants::DEFAULT_FORMAT));

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            $this->error('Error: '.$e->getMessage());

            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());

            return self::INVALID;
        }
    }
}
