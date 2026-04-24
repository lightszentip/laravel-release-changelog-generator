<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Facades;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * @see \Lightszentip\LaravelReleaseChangelogGenerator\LaravelReleaseChangelogGenerator
 * @psalm-api
 */
class LaravelReleaseChangelogGenerator extends LaravelFacade
{
    /** @psalm-pure */
    #[\Override]
    protected static function getFacadeAccessor(): string
    {
        return 'releasechangelog';
    }
}
