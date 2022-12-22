<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class AddChangelogTest extends TestCase
{
    /** @test */
    public function handle_command()
    {

        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:add", ["--type" => "feat","--message" => "first impl"]);
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $this->assertEquals("", $result);

        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog())
            ,'{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"first impl"}]}}');


    }

    /** @test */
    public function handle_command_successfull()
    {

        $this->artisan('changelog:add  --type="feat" --message="My First impl"')
            ->assertOk();
    }

    /** @test */
    public function handle_command_with_question_check()
    {
        $this->artisan('changelog:add')
            ->expectsQuestion('What is type ?','feat')
            ->expectsQuestion('What is message ?','My fist impl')
            ->assertOk();

        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog())
            ,'{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"My fist impl"}]}}');
    }

    /** @test */
    public function handle_command_wihtou_args()
    {
        $this->artisan('changelog:add')
            ->assertFailed();

        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog())
            ,'');
    }
}
