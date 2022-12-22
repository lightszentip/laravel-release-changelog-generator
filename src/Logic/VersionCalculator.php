<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

/**
 * Version Calculator
 * @since 0.0.0
 */
class VersionCalculator
{

    /**
     * increment minor version
     * @param array $yamlVersionConfig
     * @return array
     */
    public static function incrementMinor(array $yamlVersionConfig): array
    {
        $yamlVersionConfig['minor']++;
        $yamlVersionConfig['patch'] = 0;
        $yamlVersionConfig['prereleasenumber'] =0;

        return $yamlVersionConfig;
    }

    /**
     * increment patch version
     * @param array $yamlVersionConfig
     * @return array
     */
    public static function incrementPatch(array $yamlVersionConfig)
    {
        $yamlVersionConfig['patch']++;
        $yamlVersionConfig['prereleasenumber'] =0;

        return $yamlVersionConfig;
    }

    /**
     * increment pre release version
     * @param array $yamlVersionConfig
     * @return array
     */
    public static function incrementPreRelease(array $yamlVersionConfig)
    {
        if (is_null($yamlVersionConfig['prereleasenumber'])) {
            $yamlVersionConfig['prereleasenumber'] = 0;
        } else {
            $yamlVersionConfig['prereleasenumber']++;
        }

        return $yamlVersionConfig;
    }

    /**
     * increment major version
     * @param array $yamlVersionConfig
     * @return array
     */
    public static function incrementMajor(array $yamlVersionConfig)
    {
        $yamlVersionConfig['major']++;
        $yamlVersionConfig['minor'] = 0;
        $yamlVersionConfig['patch'] = 0;
        $yamlVersionConfig['prereleasenumber'] =0;

        return $yamlVersionConfig;
    }
}
