<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class ShowVersionTest extends TestCase
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
            ->artisan("changelog:show-version");
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $result = str_replace("\r", '', $result);
        $this->assertEquals("1.0.0 [] \n", $result);

    }

    /** @test */
    public function handle_command_successfull()
    {

        $this->artisan('changelog:show-version')->expectsOutput("1.0.0 [] ")
            ->assertOk();
    }

    /** @test */
    public function handle_command_with_args_successfull()
    {

        $this->artisan('changelog:show-version --format="version"')->expectsOutput("1.0.0")
            ->assertOk();
    }


    /** @test */
    public function handle_command_with_args_zero_prereleasenumber()
    {
        $this->artisan('changelog:release --releasename="fooBar 2" --type="rc"')
            ->assertOk();

        $this->artisan('changelog:show-version --format="min"')->expectsOutput("1.0.0[rc1]")
            ->assertOk();
    }

}
