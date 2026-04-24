<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class SuggestReleaseTest extends TestCase
{
    private function setUnreleased(array $types): void
    {
        $unreleased = ['name' => 'tbd', 'date' => '', 'release' => false];
        foreach ($types as $type => $messages) {
            $unreleased[$type] = array_map(fn (string $m) => ['message' => $m], $messages);
        }
        file_put_contents(FileHandler::pathChangelog(), json_encode(['unreleased' => $unreleased]));
    }

    public function test_only_fix_suggests_patch(): void
    {
        $this->setUnreleased(['fix' => ['bug fixed']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('patch', $output['type']);
    }

    public function test_feat_suggests_minor(): void
    {
        $this->setUnreleased(['feat' => ['new feature']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('minor', $output['type']);
    }

    public function test_feature_suggests_minor(): void
    {
        $this->setUnreleased(['feature' => ['another feature']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('minor', $output['type']);
    }

    public function test_breaking_suggests_major(): void
    {
        $this->setUnreleased(['breaking' => ['removed api']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('major', $output['type']);
    }

    public function test_breaking_wins_over_feat(): void
    {
        $this->setUnreleased(['feat' => ['new feature'], 'breaking' => ['removed api']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('major', $output['type']);
    }

    public function test_empty_unreleased_suggests_patch(): void
    {
        $this->setUnreleased([]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('patch', $output['type']);
    }

    public function test_chore_only_suggests_patch(): void
    {
        $this->setUnreleased(['chore' => ['updated dependencies']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertEquals('patch', $output['type']);
    }

    public function test_json_output_has_reason(): void
    {
        $this->setUnreleased(['fix' => ['bug']]);
        $this->withoutMockingConsoleOutput()->artisan('changelog:suggest-release', ['--json' => true]);
        $output = json_decode(trim(Artisan::output()), true);
        $this->assertArrayHasKey('type', $output);
        $this->assertArrayHasKey('reason', $output);
        $this->assertNotEmpty($output['reason']);
    }

    public function test_text_output_successfull(): void
    {
        $this->setUnreleased(['fix' => ['bug']]);
        $this->artisan('changelog:suggest-release')->assertOk();
    }
}
