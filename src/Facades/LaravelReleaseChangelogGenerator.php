<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Facades;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * @see \Lightszentip\LaravelReleaseChangelogGenerator\LaravelReleaseChangelogGenerator
 */
class LaravelReleaseChangelogGenerator extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'releasechangelog';
    }
}
