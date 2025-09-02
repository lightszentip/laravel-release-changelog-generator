<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Lightszentip\LaravelReleaseChangelogGenerator\Tests\TestCase;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class AddChangelogTest extends TestCase
{

    /** @test */
    public function test_handle_command()
    {

        $this->withoutMockingConsoleOutput()
            ->artisan("changelog:add", ["--type" => "feat", "--message" => "first impl"]);
        // capture the text output from the command
        $result = Artisan::output();
        // use standard text assertions
        $this->assertEquals("", $result);

        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog())
            , '{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"first impl"}]}}');


    }

    /** @test */
    public function test_handle_command_successfull()
    {

        $this->artisan('changelog:add  --type="feat" --message="My First impl"')
            ->assertOk();
    }

    /** @test */
    public function test_handle_command_with_question_check()
    {
        $this->artisan('changelog:add')
            ->expectsQuestion('What is type ?', 'feat')
            ->expectsQuestion('What is message ?', 'My fist impl')
            ->assertOk();

        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog())
            , '{"unreleased":{"name":"tbd","date":"","release":false,"feat":[{"message":"My fist impl"}]}}');
    }

    /** @test */
    public function test_handle_command_wihtou_args()
    {
        $this->artisan('changelog:add')
            ->assertFailed();

        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog())
            , '');
    }

    /** @test */
    public function test_handle_command_with_module()
    {
        $this->artisan('changelog:add --type="feat" --message="module feature" --module="modA"')
            ->assertOk();
        $expected = '{"unreleased":{"name":"tbd","date":"","release":false,"feat":{"modules":[{"name":"modA","items":[{"message":"module feature"}]}]}}}';
        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog()),
            $expected
        );
    }

    /** @test */
    public function test_handle_command_with_module_and_existing_type()
    {
        $this->artisan('changelog:add --type="feat" --message="feature 1" --module="modA"')->assertOk();
        $this->artisan('changelog:add --type="feat" --message="feature 2" --module="modB"')->assertOk();
        $expected = '{"unreleased":{"name":"tbd","date":"","release":false,"feat":{"modules":[{"name":"modA","items":[{"message":"feature 1"}]},{"name":"modB","items":[{"message":"feature 2"}]}]}}}';
        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog()),
            $expected
        );
    }

    /** @test */
    public function test_handle_command_with_module_and_without_module()
    {
        $this->artisan('changelog:add --type="feat" --message="feature without module"')->assertOk();
        $this->artisan('changelog:add --type="feat" --message="feature with module" --module="modA"')->assertOk();
        $actual = file_get_contents(FileHandler::pathChangelog());
        $actual = str_replace("\r", '', $actual);
        $expected = '{"unreleased":{"name":"tbd","date":"","release":false,"feat":{"modules":[{"name":"","items":[{"message":"feature without module"}]},{"name":"modA","items":[{"message":"feature with module"}]}]}}}';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_handle_command_with_same_module_multiple_items()
    {
        $this->artisan('changelog:add --type="feat" --message="first" --module="modA"')->assertOk();
        $this->artisan('changelog:add --type="feat" --message="second" --module="modA"')->assertOk();
        $expected = '{"unreleased":{"name":"tbd","date":"","release":false,"feat":{"modules":[{"name":"modA","items":[{"message":"first"},{"message":"second"}]}]}}}';
        $this->assertEquals(
            file_get_contents(FileHandler::pathChangelog()),
            $expected
        );
    }
}
