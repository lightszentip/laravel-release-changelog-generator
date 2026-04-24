<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Util;

/**
 * Application Constants
 *
 * @since 0.0.0
 * @psalm-immutable
 */
class Constants
{
    /**
     * Default format for changelog generation
     *
     * @var string The default output format when no specific format is specified
     */
    public const string DEFAULT_FORMAT = 'full';

    /**
     * Configuration key for version handling settings
     *
     * @var string The configuration key used to retrieve version handling preferences
     */
    public const string APP_VERSION_HANDLING = 'releasechangelog.versionhandling';

    /**
     * Configuration key for application version
     *
     * @var string The configuration key used to retrieve the current application version
     */
    public const string APP_VERSION = 'releasechangelog.version';
}
