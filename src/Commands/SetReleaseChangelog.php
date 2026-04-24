<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;

/** @psalm-api */
class SetReleaseChangelog extends BaseCommand
{
    protected $signature = 'changelog:set-release {--rn|releasename= : Name of release} {--vn|versionnumber= : Version for the release}';

    protected $description = 'Create a new Release version in file by user input version';

    private static string $ar_name = 'releasename';

    private static string $ar_version = 'versionnumber';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $name = trim($this->getArgument(self::$ar_name));
            $version = trim($this->getArgument(self::$ar_version));

            $parts = explode('.', $version);
            $major = (int) ($parts[0] ?? 0);
            $minor = (int) ($parts[1] ?? 0);
            $patch = (int) preg_replace('~\D~', '', $parts[2] ?? '0');

            $decoded_json = json_decode(file_get_contents(FileHandler::pathChangelog(true)), true);
            if (!is_array($decoded_json) || !array_key_exists('unreleased', $decoded_json)) {
                $msg = 'No release changelog exists to update';

                return $this->isJson() ? $this->errorJson($msg) : $this->failure($msg);
            }

            app(Constants::APP_VERSION_HANDLING)->updateVersion($major, $minor, $patch);
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
