<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

class VersionUtil
{
    /**
     * Updates the application version based on the specified version type.
     *
     * This method increments the version number according to semantic versioning
     * principles or updates the timestamp based on the provided type parameter.
     *
     * @param string $type The type of version update to perform. Valid values are:
     *                     'patch' - increments the patch version number
     *                     'minor' - increments the minor version number
     *                     'major' - increments the major version number
     *                     'rc' - increments the pre-release version number
     *                     'timestamp' - updates the version timestamp
     */
    public static function updateVersionByType(string $type): void
    {
        switch ($type) {
            case 'patch':
                app(Constants::APP_VERSION_HANDLING)->incrementPatch();

                break;

            case 'minor':
                app(Constants::APP_VERSION_HANDLING)->incrementMinor();

                break;

            case 'major':
                app(Constants::APP_VERSION_HANDLING)->incrementMajor();

                break;

            case 'rc':
                app(Constants::APP_VERSION_HANDLING)->incrementPreRelease();

                break;

            case 'timestamp':
                app(Constants::APP_VERSION_HANDLING)->updateTimestamp();

                break;

            default:
                break;
        }
    }

    public static function generateChangelogWithNewVersion(mixed $decoded_json, string $name, string $formatType = 'changelogversion'): mixed
    {
        $currentVersion = app(Constants::APP_VERSION)->showVersion($formatType);
        $dateNow = new \DateTimeImmutable();
        $decoded_json->{$currentVersion} = $decoded_json->unreleased;
        $decoded_json->{$currentVersion}->name = $name;
        $decoded_json->{$currentVersion}->release = true;
        $decoded_json->{$currentVersion}->date = $dateNow->format('Y-m-d H:i:s');
        $decoded_json->unreleased = ['name' => 'tbd', 'date' => '', 'release' => false];

        return $decoded_json;
    }
}
