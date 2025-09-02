<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class UpdateVersionTest extends TestCase
{

    /** @test */
    public function test_handle_command()
    {

        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:update-version", ["--type" => "patch"]);
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $result = str_replace("\r", '', $result);
        if (windows_os()) {
            $this->assertEquals("Current Version: 1.0.1 [] " . time() . "\n", $result);
        } else {
            $this->assertEquals("Current Version: 1.0.1 [] " . time() . "\n", $result);
        }

    }

    /** @test */
    public function test_handle_command_wihtou_args()
    {
        $this->artisan('changelog:update-version')
            ->assertFailed();
    }

    /** @test */
    public function test_handle_command_successfull()
    {

        $this->artisan('changelog:update-version  --type="rc"')
            ->assertOk();

        $actual = file_get_contents(FileHandler::pathVersion());
        $actual = str_replace("\r", '', $actual);
        // Remove blank lines and trim for robust comparison
        $actual = preg_replace('/^\s*\n/m', '', $actual);
        $expected = "label: v\nmajor: 1\nminor: 0\npatch: 0\nprerelease: rc\nprereleasenumber: 1\nbuildmetadata: null\ntimestamp:\n    date: '" . date("Y-m-d H:i") . "'\n    timestamp: " . time() . "\n";
        $expected = preg_replace('/^\s*\n/m', '', $expected);
        $this->assertEquals(trim($expected), trim($actual));
    }


}
