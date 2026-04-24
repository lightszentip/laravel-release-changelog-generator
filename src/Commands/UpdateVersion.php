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

            $version = app('releasechangelog.version')->showVersion(Constants::DEFAULT_FORMAT);

            if ($this->isJson()) {
                return $this->outputJson(['version' => $version]);
            }

            $this->info('Current Version: '.$version);

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        } catch (\Exception $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        }
    }
}
