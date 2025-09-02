<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

class GenerateChangelogMdCommand extends Command
{
    protected $signature = 'changelog:generate-md';

    protected $description = 'Update CHANGELOG.md file';

    public function handle()
    {
        $changelogData = json_decode(File::get(FileHandler::pathChangelog(true)), true, 512, JSON_THROW_ON_ERROR);

        if ($changelogData != null) {
            uksort($changelogData, "version_compare");
            $changelogData = array_reverse($changelogData, true);
        }

        if (!file_exists(FileHandler::pathChangelogMd())) {
            File::put(FileHandler::pathChangelogMd(), '');
        }
        if (!file_exists(Config::get('releasechangelog.markdown-view-path') . DIRECTORY_SEPARATOR . "changelog-md.blade.php")) {
            $this->error("Markdown view file not found: " . Config::get('releasechangelog.markdown-view-path') . DIRECTORY_SEPARATOR . "changelog-md.blade.php");
        }
        File::put(FileHandler::pathChangelogMd(), view(Config::get('releasechangelog.markdown-view-path') . DIRECTORY_SEPARATOR . "changelog-md", [
            'changelog' => $changelogData
        ]));
    }
}
