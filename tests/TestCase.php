<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests;

use Lightszentip\LaravelReleaseChangelogGenerator\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();



        echo "dasdsads";
        echo file_exists(resource_path('.changes'));
        //changelog.json
        if (file_exists(resource_path('.changes'))) {
            array_map('unlink', glob(resource_path('.changes').DIRECTORY_SEPARATOR.'*.*'));
            rmdir(resource_path('.changes'));
        }
        mkdir(resource_path('.changes'));

        copy(
            __DIR__.''.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'.changes'.DIRECTORY_SEPARATOR.'changelog.json',
            resource_path('.changes'.DIRECTORY_SEPARATOR.'changelog.json')
        );


        //version.yml
        if (file_exists(resource_path('.version'))) {
            array_map('unlink', glob(resource_path('.version').DIRECTORY_SEPARATOR.'*.*'));
            rmdir(resource_path('.version'));
        }
        mkdir(resource_path('.version'));

        copy(
            __DIR__.''.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'.version'.DIRECTORY_SEPARATOR.'version.yml',
            resource_path('.version'.DIRECTORY_SEPARATOR.'version.yml')
        );

    }

    protected function getPackageProviders($app): array
    {

        //changelog.json
        if (file_exists(resource_path('.changes'))) {
            array_map('unlink', glob(resource_path('.changes').DIRECTORY_SEPARATOR.'*.*'));
            rmdir(resource_path('.changes'));
        }
        mkdir(resource_path('.changes'));

        copy(
            __DIR__.''.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'.changes'.DIRECTORY_SEPARATOR.'changelog.json',
            resource_path('.changes'.DIRECTORY_SEPARATOR.'changelog.json')
        );


        //version.yml
        if (file_exists(resource_path('.version'))) {
            array_map('unlink', glob(resource_path('.version').DIRECTORY_SEPARATOR.'*.*'));
            rmdir(resource_path('.version'));
        }
        mkdir(resource_path('.version'));

        copy(
            __DIR__.''.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'.version'.DIRECTORY_SEPARATOR.'version.yml',
            resource_path('.version'.DIRECTORY_SEPARATOR.'version.yml')
        );
        return [
            ServiceProvider::class,
        ];
    }



    public function getEnvironmentSetUp($app)
    {

    }




}
