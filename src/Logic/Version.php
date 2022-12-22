<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

use Illuminate\Support\Facades\Config;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;

/**
 *
 */
class Version
{

    public const REPLACE_VALUE = array('{major}', '{minor}', '{patch}', '{prerelease}', '{prereleasenumber}', '{buildmetadata}', '{date}','{timestamp}');

    /**
     * show Version by format type
     * @param string $type
     * @return string The current version
     */
    public function showVersion(string $type): string
    {
        $existsFormats = Config::get('releasechangelog.version_formats');
        $result = 'not defined';

        if (array_key_exists($type, $existsFormats)) {
            $version = app(Constants::APP_VERISON_HANDLING)->getContent();
            $result = str_replace(Version::REPLACE_VALUE, [$version['major'], $version['minor'], $version['patch'], $version['prerelease'], $version['prereleasenumber'], $version['buildmetadata'], $version['timestamp']['date'],$version['timestamp']['timestamp']], $existsFormats[$type]);
        }
        if (Config::get('releasechangelog.prerelease', false)) {
            $result = str_replace('.{prerelease}{prereleasenumber}', '', $result);
        }
        return $result;
    }
}
