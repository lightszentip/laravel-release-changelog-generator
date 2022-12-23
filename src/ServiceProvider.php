<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\AddChangelog;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\ReleaseChangelog;
use Lightszentip\LaravelReleaseChangelogGenerator\Commands\ShowVersion;
use Lightszentip\LaravelReleaseChangelogGenerator\Logic\Version;
use Lightszentip\LaravelReleaseChangelogGenerator\Logic\VersionHandling;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'releasechangelog');


        $this->app->singleton(Constants::APP_VERISON_HANDLING, function () {
            return new VersionHandling();
        });
        $this->app->singleton('releasechangelog.version', function () {
            return new Version();
        });
    }

    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                ReleaseChangelog::class,
                AddChangelog::class,
                ShowVersion::class
            ]);
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('releasechangelog.php'),
            ], 'config');

            $this->publishes([

                // Views
                __DIR__.'/../resources/.version/version.yml' => resource_path('.version/version.yml'),
                __DIR__.'/../resources/.changes/changelog.json' => resource_path('.changes/changelog.json'),

            ], 'resources');

        }

        Blade::directive(
            Config::get('releasechangelog.blade-directive', 'releasechangelog'),
            function ($format = Constants::DEFAULT_FORMAT) {
                return "<?php echo app('releasechangelog.version')->showVersion($format); ?>";
            }
        );
    }
}
