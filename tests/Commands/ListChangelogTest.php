<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class ListChangelogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents(FileHandler::pathChangelog(), json_encode([
            'unreleased' => ['name' => 'tbd', 'date' => '', 'release' => false, 'feat' => [['message' => 'upcoming']]],
            '1.0.0' => ['name' => 'First Release', 'date' => '2024-01-01', 'release' => true, 'feat' => [['message' => 'a'], ['message' => 'b']], 'fix' => [['message' => 'c']]],
            '1.1.0' => ['name' => 'Second Release', 'date' => '2024-06-01', 'release' => true, 'feat' => [['message' => 'd']]],
        ]));
    }

    public function test_handle_command_successfull(): void
    {
        $this->artisan('changelog:list')->assertOk();
    }

    public function test_handle_command_excludes_unreleased(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:list', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertIsArray($output);
        foreach ($output as $item) {
            $this->assertNotEquals('unreleased', $item['version']);
        }
    }

    public function test_handle_command_json_structure(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:list', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertIsArray($output);
        $this->assertCount(2, $output);
        $first = $output[0];
        $this->assertArrayHasKey('version', $first);
        $this->assertArrayHasKey('name', $first);
        $this->assertArrayHasKey('date', $first);
        $this->assertArrayHasKey('count', $first);
    }

    public function test_handle_command_sorted_descending(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:list', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('1.1.0', $output[0]['version']);
        $this->assertEquals('1.0.0', $output[1]['version']);
    }

    public function test_handle_command_entry_count(): void
    {
        $this->withoutMockingConsoleOutput()->artisan('changelog:list', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $v100 = array_values(array_filter($output, fn ($r) => $r['version'] === '1.0.0'))[0];
        $this->assertEquals(3, $v100['count']);
    }

    public function test_handle_command_empty_changelog(): void
    {
        file_put_contents(FileHandler::pathChangelog(), json_encode(['unreleased' => ['name' => 'tbd', 'date' => '', 'release' => false]]));
        $this->withoutMockingConsoleOutput()->artisan('changelog:list', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertIsArray($output);
        $this->assertCount(0, $output);
    }
}
