<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

/**
 * Util class for File Configs and handling.
 *
 * @since 0.1.0
 */
class FileHandler
{
    /**
     * Get path to changelog file.
     *
     * @param bool $checkFileExistsAndCreate default false
     */
    public static function pathChangelog(bool $checkFileExistsAndCreate = false): string
    {
        $path = Config::get('releasechangelog.path').\DIRECTORY_SEPARATOR.'.changes'.\DIRECTORY_SEPARATOR.'changelog.json';
        if ($checkFileExistsAndCreate && !file_exists($path)) {
            File::put($path, '');
        }

        return $path;
    }

    /**
     * Get path to version file.
     */
    public static function pathVersion(): string
    {
        return resource_path().\DIRECTORY_SEPARATOR.'.version'.\DIRECTORY_SEPARATOR.'version.yml';
    }

    /**
     * Get path to markdown changelog file.
     *
     * Constructs the full file path to the CHANGELOG.md file by combining
     * the configured markdown path with the changelog filename.
     *
     * @return string The complete file path to the CHANGELOG.md file
     */
    public static function pathChangelogMd()
    {
        return Config::get('releasechangelog.markdown-path').\DIRECTORY_SEPARATOR.'CHANGELOG.md';
    }
}
