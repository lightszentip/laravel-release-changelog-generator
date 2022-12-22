<?php

// config for Lightszentip/LaravelReleaseChangelogGenerator
return [

    /**
     * Path where the changelog file exists or should create
     */
    'path' => resource_path(),
    'version_path' => resource_path(),

    /**
     * Type of version mode change
     */
    //'version_mode' => 'increment', # or 'increment',

    /**
     * Version default format
     */
    'version_format_default' => 'version {major}.{minor}.{patch}.{prerelease}{prereleasenumber} [{buildmetadata}] (timestamp {timestamp})',

    'version_formats' => [
        'full' => 'version {major}.{minor}.{patch} [{buildmetadata}] (timestamp {timestamp})',
        'changelogversion' => '{major}.{minor}.{patch}.{prerelease}{prereleasenumber}',
        'min' => '{$major}.{$minor}.{$patch}[.?={$prerelease}{$prereleasenumber}]',
        'version' => '{major}.{minor}.{patch}',
        'minor' => '{minor}',
        'major' => '{major}',
        'patch' => '{patch}',
        'buildmetadata' => '{buildmetadata}',
        'commit' => '{commit}',
        'prerelease' => '{prerelease}{prereleasenumber}',
    ],

    /**
     * Is prerelease active
     */
    'prerelease' => true,

    'blade-directive ' => 'version',

];
