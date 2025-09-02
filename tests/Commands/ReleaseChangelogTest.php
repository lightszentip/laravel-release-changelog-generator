<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class ReleaseChangelogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents(FileHandler::pathChangelog(), '{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"first impl"}]}}');
    }

    public function test_handle_command()
    {
        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:release", ["--releasename" => "FooBar 1"]);
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $this->assertEquals("", $result);


        $this->assertEquals(
            '1.0.1',
            app('releasechangelog.version')->showVersion('min')
        );
    }

    public function test_handle_command_successfull()
    {

        $this->artisan('changelog:release  --releasename="FooBar 1"')->assertOk();
    }

    public function test_handle_command_with_question_check()
    {
        $this->artisan('changelog:release')
            ->expectsQuestion('What is releasename ?', 'fooBar 1')
            ->assertOk();

        $this->assertEquals(
            preg_replace('/\"date\"\:\".*?\"\,/s', '"date":"",', file_get_contents(FileHandler::pathChangelog()))
            , '{"unreleased":{"name":"tbd","date":"","release":false},"1.0.1":{"name":"fooBar 1","date":"","release":true,"feat":[{"message":"first impl"}]}}');
    }

    public function test_handle_command_major()
    {
        $this->artisan('changelog:release --releasename="fooBar 1" --type="major"')
            ->assertOk();

        $this->assertEquals(
            preg_replace('/\"date\"\:\".*?\"\,/s', '"date":"",', file_get_contents(FileHandler::pathChangelog()))
            , '{"unreleased":{"name":"tbd","date":"","release":false},"2.0.0":{"name":"fooBar 1","date":"","release":true,"feat":[{"message":"first impl"}]}}');
    }

    public function test_handle_command_minor()
    {
        $this->artisan('changelog:release --releasename="fooBar 1" --type="minor"')
            ->assertOk();

        $this->assertEquals(
            preg_replace('/\"date\"\:\".*?\"\,/s', '"date":"",', file_get_contents(FileHandler::pathChangelog()))
            , '{"unreleased":{"name":"tbd","date":"","release":false},"1.1.0":{"name":"fooBar 1","date":"","release":true,"feat":[{"message":"first impl"}]}}');
    }

    public function test_handle_command_patch()
    {
        $this->artisan('changelog:release --releasename="fooBar 1" --type="patch"')
            ->assertOk();

        $this->assertEquals(
            preg_replace('/\"date\"\:\".*?\"\,/s', '"date":"",', file_get_contents(FileHandler::pathChangelog()))
            , '{"unreleased":{"name":"tbd","date":"","release":false},"1.0.1":{"name":"fooBar 1","date":"","release":true,"feat":[{"message":"first impl"}]}}');
    }


    public function test_handle_command_prerelease()
    {
        $this->artisan('changelog:release --releasename="fooBar 1" --type="rc"')
            ->assertOk();

        $this->assertEquals(
            preg_replace('/\"date\"\:\".*?\"\,/s', '"date":"",', file_get_contents(FileHandler::pathChangelog()))
            , '{"unreleased":{"name":"tbd","date":"","release":false},"1.0.0.rc1":{"name":"fooBar 1","date":"","release":true,"feat":[{"message":"first impl"}]}}');
    }

    public function test_handle_command_wihtou_args()
    {
        $this->artisan('changelog:release')
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
