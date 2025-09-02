<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

/**
 * Version Calculator
 * @since 0.0.0
 */
class VersionCalculator
{

    /**
     * Increments the minor version number and resets patch and prerelease numbers to zero
     *
     * @param array $yamlVersionConfig The version configuration array containing major, minor, patch, and prereleasenumber keys
     * @return array The updated version configuration array with incremented minor version and reset patch and prerelease numbers
     */
    public static function incrementMinor(array $yamlVersionConfig): array
    {
        $yamlVersionConfig['minor']++;
        $yamlVersionConfig['patch'] = 0;
        $yamlVersionConfig['prereleasenumber'] = 0;

        return $yamlVersionConfig;
    }

    /**
     * Increments the patch version number and resets the prerelease number to zero
     *
     * @param array $yamlVersionConfig The version configuration array containing major, minor, patch, and prereleasenumber keys
     * @return array The updated version configuration array with incremented patch version and reset prerelease number
     */
    public static function incrementPatch(array $yamlVersionConfig)
    {
        $yamlVersionConfig['patch']++;
        $yamlVersionConfig['prereleasenumber'] = 0;

        return $yamlVersionConfig;
    }

    /**
     * Increments the prerelease version number or initializes it to 0 if null
     *
     * @param array $yamlVersionConfig The version configuration array containing major, minor, patch, and prereleasenumber keys
     * @return array The updated version configuration array with incremented or initialized prerelease number
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
     * Increments the major version number and resets minor, patch, and prerelease numbers to zero
     *
     * @param array $yamlVersionConfig The version configuration array containing major, minor, patch, and prereleasenumber keys
     * @return array The updated version configuration array with incremented major version and reset minor, patch, and prerelease numbers
     */
    public static function incrementMajor(array $yamlVersionConfig)
    {
        $yamlVersionConfig['major']++;
        $yamlVersionConfig['minor'] = 0;
        $yamlVersionConfig['patch'] = 0;
        $yamlVersionConfig['prereleasenumber'] = 0;

        return $yamlVersionConfig;
    }

    /**
     * Updates the version configuration with specific major, minor, and patch values and resets prerelease number to zero
     *
     * @param array $yamlVersionConfig The version configuration array containing major, minor, patch, and prereleasenumber keys
     * @param int $major The new major version number to set
     * @param int $minor The new minor version number to set
     * @param int $patch The new patch version number to set
     * @return array The updated version configuration array with the specified version numbers and reset prerelease number
     */
    public static function updateVersion(array $yamlVersionConfig, int $major, int $minor, int $patch)
    {
        $yamlVersionConfig['major'] = $major;
        $yamlVersionConfig['minor'] = $minor;
        $yamlVersionConfig['patch'] = $patch;
        $yamlVersionConfig['prereleasenumber'] = 0;

        return $yamlVersionConfig;
    }
}
