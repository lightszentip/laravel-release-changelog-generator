<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Illuminate\Support\Facades\File;

class GenerateChangelogMdCommand extends Command
{
    protected $signature = 'changelog:generate-md';

    protected $description = 'Update CHANGELOG.md file';

    public function handle()
    {
        $changelogData = json_decode(
            File::get(FileHandler::pathChangelog(true)),
            true
        );

        if ($changelogData != null) {
            krsort($changelogData);
        }

        File::put(FileHandler::pathChangelogMd(), view( "changelog-md", [
            'changelog' => $changelogData
        ]));
    }
}
