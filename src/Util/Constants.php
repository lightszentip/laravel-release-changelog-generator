<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

/**
 * Application Constants
 *
 * This class contains constant values used throughout the Laravel Release Changelog Generator application.
 * These constants define configuration keys, default values, and other application-wide settings.
 *
 * @since 0.0.0
 */
class Constants
{
    /**
     * Default format for changelog generation
     *
     * @var string The default output format when no specific format is specified
     */
    public const DEFAULT_FORMAT = 'full';

    /**
     * Configuration key for version handling settings
     *
     * @var string The configuration key used to retrieve version handling preferences
     */
    public const APP_VERSION_HANDLING = 'releasechangelog.versionhandling';

    /**
     * Configuration key for application version
     *
     * @var string The configuration key used to retrieve the current application version
     */
    public const APP_VERSION = 'releasechangelog.version';
}
