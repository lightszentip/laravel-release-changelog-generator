<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

class VersionCalculator
{
    public static function incrementMinor(array $yamlVersionConfig): array
    {
        $yamlVersionConfig['minor']++;
        $yamlVersionConfig['patch'] = 0;
        $yamlVersionConfig['prereleasenumber'] =0;

        return $yamlVersionConfig;
    }

    public static function incrementPatch(array $yamlVersionConfig)
    {
        $yamlVersionConfig['patch']++;
        $yamlVersionConfig['prereleasenumber'] =0;

        return $yamlVersionConfig;
    }

    public static function incrementPreRelease(array $yamlVersionConfig)
    {
        if (is_null($yamlVersionConfig['prereleasenumber'])) {
            $yamlVersionConfig['prereleasenumber'] = 0;
        } else {
            $yamlVersionConfig['prereleasenumber']++;
        }

        return $yamlVersionConfig;
    }

    public static function incrementMajor(array $yamlVersionConfig)
    {
        $yamlVersionConfig['major']++;
        $yamlVersionConfig['minor'] = 0;
        $yamlVersionConfig['patch'] = 0;
        $yamlVersionConfig['prereleasenumber'] =0;

        return $yamlVersionConfig;
    }
}
