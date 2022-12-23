<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

class VersionUtil
{

    public static function updateVersionByType(string $type): void
    {
        switch ($type) {
            case 'patch':
                app(Constants::APP_VERISON_HANDLING)->incrementPatch();
                break;
            case 'minor':
                app(Constants::APP_VERISON_HANDLING)->incrementMinor();
                break;
            case 'major':
                app(Constants::APP_VERISON_HANDLING)->incrementMajor();
                break;
            case 'rc':
                app(Constants::APP_VERISON_HANDLING)->incrementPreRelease();
                break;
            case 'timestamp':
                app(Constants::APP_VERISON_HANDLING)->updateTimestamp();
                break;
        }

    }
}
