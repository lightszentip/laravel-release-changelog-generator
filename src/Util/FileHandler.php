<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

/**
 * Util class for File Configs and handling
 * @since 0.1.0
 */
class FileHandler
{
    /**
     * Get path to changelog file
     * @return string
     */
    public static function pathChangelog(): string
    {
        return Config::get('releasechangelog.path').DIRECTORY_SEPARATOR.'.changes'.DIRECTORY_SEPARATOR.'changelog.json';
    }

    /**
     * Get path to version file
     * @return string
     */
    public static function pathVersion(): string
    {
        return resource_path().DIRECTORY_SEPARATOR.'.version'.DIRECTORY_SEPARATOR.'version.yml';
    }
}
