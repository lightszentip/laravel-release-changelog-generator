<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class UpdateVersionTest extends TestCase
{

    /** @test */
    public function handle_command()
    {

        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:update-version", ["--type" => "patch"]);
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        if (windows_os()) {
            $this->assertEquals("Current Version: 1.0.1 [] ".time()."\r\n", $result);
        } else {
            $this->assertEquals("Current Version: 1.0.1 [] ".time()."\n", $result);
        }

    }

    /** @test */
    public function handle_command_wihtou_args()
    {
        $this->artisan('changelog:update-version')
            ->assertFailed();
    }

    /** @test */
    public function handle_command_successfull()
    {

        $this->artisan('changelog:update-version  --type="rc"')
            ->assertOk();


        $this->assertEquals(
            'label: v
major: 1
minor: 0
patch: 0
prerelease: rc
prereleasenumber: 1
buildmetadata: null
timestamp:
    date: \''.date("Y-m-d H:i").'\'
    timestamp: '.time().'
', file_get_contents(FileHandler::pathVersion()));
    }


}
