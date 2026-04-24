<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class ShowChangelogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents(FileHandler::pathChangelog(), json_encode([
            'unreleased' => ['name' => 'tbd', 'date' => '', 'release' => false, 'feat' => [['message' => 'upcoming feature']]],
            '1.0.0' => ['name' => 'First Release', 'date' => '2024-01-01', 'release' => true, 'fix' => [['message' => 'bug fix']]],
        ]));
    }

    public function test_handle_command_unreleased_default(): void
    {
        $this->artisan('changelog:show')->assertOk();
    }

    public function test_handle_command_unreleased_flag(): void
    {
        $this->artisan('changelog:show --unreleased')->assertOk();
    }

    public function test_handle_command_specific_version(): void
    {
        $this->artisan('changelog:show --ver=1.0.0')->assertOk();
    }

    public function test_handle_command_version_not_found(): void
    {
        $this->artisan('changelog:show --ver=9.9.9')->assertFailed();
    }

    public function test_handle_command_unreleased_json(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:show', ['--unreleased' => true, '--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertIsArray($output);
        $this->assertArrayHasKey('feat', $output);
        $this->assertEquals('upcoming feature', $output['feat'][0]['message']);
    }

    public function test_handle_command_version_json(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:show', ['--ver' => '1.0.0', '--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertIsArray($output);
        $this->assertEquals('First Release', $output['name']);
        $this->assertArrayHasKey('fix', $output);
    }

    public function test_handle_command_version_not_found_json(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:show', ['--ver' => '9.9.9', '--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertArrayHasKey('error', $output);
    }
}
