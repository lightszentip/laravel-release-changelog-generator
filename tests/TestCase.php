<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests;

use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {

    }

    protected function getPackageProviders($app): array
    {
        copy(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'changelog-md.blade.php',
            'changelog-md.blade.php'
        );
        //changelog.json
        if (file_exists(resource_path('.changes'))) {
            array_map('unlink', glob(resource_path('.changes') . DIRECTORY_SEPARATOR . '*.*'));
            rmdir(resource_path('.changes'));
        }
        if (file_exists(resource_path('views'))) {
            array_map('unlink', glob(resource_path('views') . DIRECTORY_SEPARATOR . '*.*'));
            array_map('unlink', glob(resource_path('views') . DIRECTORY_SEPARATOR . "errors" . DIRECTORY_SEPARATOR . '*.*'));
            if (file_exists(resource_path('views') . DIRECTORY_SEPARATOR . "errors")) {
                rmdir(resource_path('views') . DIRECTORY_SEPARATOR . "errors");
            }
            rmdir(resource_path('views'));
        }
        mkdir(resource_path('.changes'));
        mkdir(resource_path('views'));

        $res_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
        copy(
            $res_path . '.changes' . DIRECTORY_SEPARATOR . 'changelog.json',
            resource_path('.changes' . DIRECTORY_SEPARATOR . 'changelog.json')
        );
        copy($res_path . 'views' . DIRECTORY_SEPARATOR . 'changelog-md.blade.php',
            resource_path('views' . DIRECTORY_SEPARATOR . 'changelog-md.blade.php')
        );


        //version.yml
        if (file_exists(resource_path('.version'))) {
            array_map('unlink', glob(resource_path('.version') . DIRECTORY_SEPARATOR . '*.*'));
            rmdir(resource_path('.version'));
        }
        mkdir(resource_path('.version'));

        copy(
            __DIR__ . '' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . '.version' . DIRECTORY_SEPARATOR . 'version.yml',
            resource_path('.version' . DIRECTORY_SEPARATOR . 'version.yml')
        );

        return [
            ServiceProvider::class,
        ];
    }


    public function getEnvironmentSetUp($app)
    {

    }


}
