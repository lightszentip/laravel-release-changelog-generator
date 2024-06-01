<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;


use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class SetReleaseChangelogTest extends TestCase
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
            ->artisan("changelog:set-release", ["--releasename" => "FooBar 1", "--versionnumber" => '1.3.2']);
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $this->assertEquals("", $result);

        $this->assertEquals(
            '1.3.2',
            app('releasechangelog.version')->showVersion('min')
        );


    }

    /** @test */
    public function handle_command_successfull()
    {

        $this->artisan('changelog:set-release --releasename="Second Edition Bar" --versionnumber="1.3.2"')
            ->assertOk();
    }

    /** @test */
    public function handle_command_with_question_check()
    {
        $this->artisan('changelog:set-release')
            ->expectsQuestion('What is releasename ?', 'fooBar 1')
            ->expectsQuestion('What is versionnumber ?', '2.4.1')
            ->assertOk();

        $this->assertEquals(
            preg_replace('/\"date\"\:\".*?\"\,/s', '"date":"",', file_get_contents(FileHandler::pathChangelog()))
            , '{"unreleased":{"name":"tbd","date":"","release":false},"2.4.1":{"name":"fooBar 1","date":"","release":true,"feat":[{"message":"first impl"}]}}');
    }

    /** @test */
    public function handle_command_wihtou_args()
    {
        $this->artisan('changelog:set-release')
            ->assertFailed();

        $this->artisan('changelog:set-release', ["--releasename" => "FooBar 1"])
            ->assertFailed();

        $this->assertEquals(
            'label: v
major: 1
minor: 0
patch: 0
prerelease: rc
prereleasenumber: 0
buildmetadata:
timestamp:
  date:
  timestamp:
', file_get_contents(FileHandler::pathVersion()));
    }
}
