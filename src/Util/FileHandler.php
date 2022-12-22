<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class FileHandler
{
    public static function pathChangelog(): string
    {
        /** @var Path to changlog file $path */
        return Config::get('releasechangelog.path').DIRECTORY_SEPARATOR.'.changes'.DIRECTORY_SEPARATOR.'changelog.json';
    }

    public static function pathVersion(): string
    {
        /** @var Path to changlog file $path */
        $path = resource_path().DIRECTORY_SEPARATOR.'.version'.DIRECTORY_SEPARATOR.'version.yml';

        if (! file_exists($path)) {
            File::put($path, '');
        }
        return $path;
    }
}
