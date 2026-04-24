<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;

/** @psalm-api */
class GenerateChangelogMdCommand extends BaseCommand
{
    protected $signature = 'changelog:generate-md';

    protected $description = 'Update CHANGELOG.md file';

    public function handle(): void
    {
        $changelogData = json_decode(File::get(FileHandler::pathChangelog(true)), true, 512, JSON_THROW_ON_ERROR);

        if (is_array($changelogData)) {
            uksort($changelogData, 'version_compare');
            $changelogData = array_reverse($changelogData, true);
        }

        if (!file_exists(FileHandler::pathChangelogMd())) {
            File::put(FileHandler::pathChangelogMd(), '');
        }

        $viewPath = Config::get('releasechangelog.markdown-view-path').DIRECTORY_SEPARATOR.'changelog-md.blade.php';
        if (!file_exists($viewPath)) {
            $this->error('Markdown view file not found: '.$viewPath);
        }

        File::put(FileHandler::pathChangelogMd(), view(
            Config::get('releasechangelog.markdown-view-path').DIRECTORY_SEPARATOR.'changelog-md',
            ['changelog' => $changelogData]
        ));
    }
}
