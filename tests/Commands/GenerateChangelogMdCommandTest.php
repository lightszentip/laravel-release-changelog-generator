<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class GenerateChangelogMdCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents(FileHandler::pathChangelog(), '{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"first impl"}]}}');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists('changelog-md.blade.php')) {
            File::delete('changelog-md.blade.php');
        }
    }

    public function test_handle_command()
    {
        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:generate-md");
        $result = Artisan::output();
        $result = str_replace("\r", '', $result);
        $this->assertEquals("", $result);
    }

    public function test_handle_command_successfull()
    {
        $this->artisan('changelog:generate-md')->assertOk();
    }

    public function test_handle_command_with_custom_path()
    {
        $old_path = Config::get('releasechangelog.markdown-path');
        $target_path = $old_path . DIRECTORY_SEPARATOR . "public";
        Config::set('releasechangelog.markdown-path', $target_path);
        $this->assertStringContainsString($target_path, FileHandler::pathChangelogMd());
        $this->artisan('changelog:generate-md')->assertOk();
        $result = File::get(FileHandler::pathChangelogMd());
        $result = str_replace("\r", '', $result);
        $this->compare_template($result);
    }

    public function handle_command_with_custom_view_path()
    {
        Config::set('releasechangelog.markdown-view-path', 'abc');
        $this->artisan('changelog:generate-md')->assertFailed();
    }

    public function test_handle_command_with_generate_correct_file()
    {
        $this->artisan('changelog:generate-md')->assertOk();
        $result = File::get(FileHandler::pathChangelogMd());
        $result = str_replace("\r", '', $result);
        $this->compare_template($result);
    }

    public function test_handle_command_with_generate_correct_file_with_ordered_versions()
    {
        file_put_contents(
            FileHandler::pathChangelog(),
            json_encode(array(
                "1.0.1" => array(
                    "name" => "Version 1.0.1",
                    "date" => "",
                    "release" => true,
                    "Added" => array(
                        array("message" => "Added something"),
                    ),
                ),
                "1.0.9" => array(
                    "name" => "Version 1.0.9",
                    "date" => "",
                    "release" => true,
                    "Added" => array(
                        array("message" => "Added something 9"),
                    ),
                ),
                "1.0.11" => array(
                    "name" => "Version 1.0.11",
                    "date" => "",
                    "release" => true,
                    "Added" => array(
                        array("message" => "Added something 11"),
                    ),
                ),
            ))
        );
        $this->artisan('changelog:generate-md')->assertOk();
        $result = File::get(FileHandler::pathChangelogMd());
        $result = str_replace("\r", '', $result);
        $this->compare_template_ordered($result);
    }

    /**
     * @param string $result
     * @return null
     */
    private function compare_template(string $result)
    {
        $result = str_replace("\r", '', $result);
        $expected = file_get_contents(__DIR__ . '/changelog-md.blade.php.expected');
        $expected = str_replace("\r", '', $expected);
        // Remove all blank lines and trim
        $result = preg_replace('/^\s*\n/m', '', $result);
        $expected = preg_replace('/^\s*\n/m', '', $expected);
        $this->assertEquals(trim($expected), trim($result));
    }

    /**
     * @param string $result
     * @return null
     */
    private function compare_template_ordered(string $result)
    {
        $result = str_replace("\r", '', $result);
        $expected = file_get_contents(__DIR__ . '/changelog-md.blade.ordered.expected');
        $expected = str_replace("\r", '', $expected);
        $result = preg_replace('/^\s*\n/m', '', $result);
        $expected = preg_replace('/^\s*\n/m', '', $expected);
        $this->assertEquals(trim($expected), trim($result));
    }
}
