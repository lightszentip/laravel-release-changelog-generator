<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;

class GenerateChangelogMdCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents(FileHandler::pathChangelog(), '{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"first impl"}]}}');
    }

    /** @test */
    public function handle_command()
    {
        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:generate-md");
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $this->assertEquals("", $result);


    }

    /** @test */
    public function handle_command_successfull()
    {

        $this->artisan('changelog:generate-md')->assertOk();
    }


    /** @test */
    public function handle_command_with_custom_path()
    {
        $old_path = Config::get('releasechangelog.markdown-path');
        $target_path = $old_path . DIRECTORY_SEPARATOR . "public";
        Config::set('releasechangelog.markdown-path', $target_path);
        $this->assertStringContainsString($target_path, FileHandler::pathChangelogMd());
        $this->artisan('changelog:generate-md')->assertOk();
        $result = File::get(FileHandler::pathChangelogMd());
        $this->compare_template($result);
    }

    public function handle_command_with_custom_view_path()
    {
        Config::set('releasechangelog.markdown-view-path', 'abc');
        $this->artisan('changelog:generate-md')->assertFailed();
    }
    

    /** @test */
    public function handle_command_with_generate_correct_file()
    {
        $this->artisan('changelog:generate-md')->assertOk();

        $result = File::get(FileHandler::pathChangelogMd());
        $this->compare_template($result);
    }

    /**
     * @param string $result
     * @return null
     */
    private function compare_template(string $result)
    {
        $this->assertEquals($result, '# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
## [Unreleased]

### Feat

    - first impl



');
    }
}
