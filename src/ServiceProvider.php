<?php


/*
 * This file is part of Laravel Release Changelog Generator
 *
 * (c) lightszentip <lightszentip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lightszentip\LaravelReleaseChangelogGenerator;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\AddChangelog;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\GenerateChangelogMdCommand;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\ReleaseChangelog;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\SetReleaseChangelog;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\ShowVersion;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\UpdateVersion;
use Lightszentip\LaravelReleaseChangelogGenerator\Logic\Version;
use Lightszentip\LaravelReleaseChangelogGenerator\Logic\VersionHandling;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'releasechangelog');

        $this->app->singleton(Constants::APP_VERSION_HANDLING, static function () {
            return new VersionHandling();
        });
        $this->app->singleton('releasechangelog.version', static function () {
            return new Version();
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'releasechangelog');
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                ReleaseChangelog::class,
                AddChangelog::class,
                UpdateVersion::class,
                ShowVersion::class,
                SetReleaseChangelog::class,
                GenerateChangelogMdCommand::class,
            ]);
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('releasechangelog.php'),
            ], 'config');

            $this->publishes([
                // Views
                __DIR__.'/../resources/.version/version.yml' => resource_path('.version/version.yml'),
                __DIR__.'/../resources/.changes/changelog.json' => resource_path('.changes/changelog.json'),
                __DIR__.'/../resources/views/changelog-md.blade.php' => resource_path('views/changelog-md.blade.php'),
            ], 'resources');
        }

        Blade::directive(
            Config::get('releasechangelog.blade-directive', 'releasechangelog'),
            static function ($format = Constants::DEFAULT_FORMAT) {
                return "<?php echo app('releasechangelog.version')->showVersion({$format}); ?>";
            }
        );
    }
}
