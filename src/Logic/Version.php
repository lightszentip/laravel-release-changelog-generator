<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

use Illuminate\Support\Facades\Config;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;

class Version
{

    public const REPLACE_VALUE = array('{major}', '{minor}', '{patch}', '{prerelease}', '{prereleasenumber}', '{buildmetadata}', '{date}', '{timestamp}');

    /**
     * Generates a formatted version string based on the specified format type.
     *
     * This method retrieves version information and applies formatting rules to create
     * a version string according to predefined format templates. It handles prerelease
     * versions by conditionally removing prerelease components when the prerelease
     * number is zero or when prerelease mode is disabled in configuration.
     *
     * @param string $type The format type key used to lookup the version format template
     *                     from the 'releasechangelog.version_formats' configuration
     * @return string The formatted version string based on the specified type, or
     *                'not defined' if the format type doesn't exist in configuration
     */
    public function showVersion(string $type): string
    {
        $existsFormats = Config::get('releasechangelog.version_formats');
        $result = 'not defined';

        if (array_key_exists($type, $existsFormats)) {
            $version = app(Constants::APP_VERSION_HANDLING)->getContent();
            if ($version['prereleasenumber'] === 0) {
                //REPLACE the prerelease part from the version string
                $existsFormats[$type] = str_replace('[{prerelease}{prereleasenumber}]', '', $existsFormats[$type]);
                $existsFormats[$type] = str_replace('.{prerelease}{prereleasenumber}', '', $existsFormats[$type]);
                $existsFormats[$type] = str_replace('-{prerelease}{prereleasenumber}', '', $existsFormats[$type]);
            }
            $result = str_replace(Version::REPLACE_VALUE, [$version['major'], $version['minor'], $version['patch'], $version['prerelease'], $version['prereleasenumber'], $version['buildmetadata'], $version['timestamp']['date'], $version['timestamp']['timestamp']], $existsFormats[$type]);

        }
        if (Config::get('releasechangelog.prerelease', false)) {
            $result = str_replace('.{prerelease}{prereleasenumber}', '', $result);
        }
        return $result;
    }
}
