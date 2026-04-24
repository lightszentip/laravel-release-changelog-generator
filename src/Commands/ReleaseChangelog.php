<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;

/** @psalm-api */
class ReleaseChangelog extends BaseCommand
{
    protected $signature = 'changelog:release {--rn|releasename= : Name of release} {--t|type=patch : Which update the current version - patch, minor, major, rc, timestamp}';

    protected $description = 'Create a new Release version in file';

    private static string $ar_name = 'releasename';

    private static string $ar_type = 'type';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if (!file_exists(FileHandler::pathChangelog())) {
            File::put(FileHandler::pathChangelog(), '');
        }

        try {
            $type = trim($this->getArgument(self::$ar_type));
            $name = trim($this->getArgument(self::$ar_name));

            if (!in_array($type, ['patch', 'minor', 'major', 'rc', 'timestamp'], true)) {
                $msg = 'Please use timestamp, rc, patch, minor or major for a release';

                return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
            }

            $decoded_json = json_decode(file_get_contents(FileHandler::pathChangelog()), true);
            if (!is_array($decoded_json) || !array_key_exists('unreleased', $decoded_json)) {
                $msg = 'No release changelog exists to update';

                return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
            }

            VersionUtil::updateVersionByType($type);
            $decoded_json = VersionUtil::generateChangelogWithNewVersion($decoded_json, $name);
            file_put_contents(FileHandler::pathChangelog(), json_encode($decoded_json));

            if ($this->isJson()) {
                $version = app('releasechangelog.version')->showVersion(Constants::DEFAULT_FORMAT);

                return $this->outputJson(['success' => true, 'version' => $version]);
            }

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        } catch (\Exception $e) {
            return $this->isJson() ? $this->errorJson($e->getMessage()) : $this->failure('Error: '.$e->getMessage());
        }
    }
}
