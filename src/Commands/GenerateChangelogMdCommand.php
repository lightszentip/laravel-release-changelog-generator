<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
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
            uksort($changelogData, "version_compare");
            $changelogData = array_reverse($changelogData, true);
        }

        File::put(FileHandler::pathChangelogMd(), view(  Config::get('releasechangelog.markdown-view-path')."changelog-md", [
            'changelog' => $changelogData
        ]));
    }
}
