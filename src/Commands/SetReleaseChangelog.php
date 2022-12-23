<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SetReleaseChangelog extends Command
{
    private static string $ar_name = 'releasename';

    private static string $ar_version = 'versionnumber';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:set-release {--rn|releasename= : Name of release} {--vn|versionnumber= : Version for the release}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Release version in file by user input version';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $name = trim($this->getArgument(SetReleaseChangelog::$ar_name));
            $version = trim($this->getArgument(SetReleaseChangelog::$ar_version));

            $versionSplit = explode('.',$version);
            $major = 0;
            $minor = 0;
            $patch = 0;
            for($i = 0;$i < count($versionSplit);$i++) {
                switch ($i) {
                    case 0:
                        $major = $versionSplit[$i];
                        break;
                    case 1:
                        $minor = $versionSplit[$i];
                        break;
                    case 2:
                        $patch = preg_replace('~\D~', '', $versionSplit[$i]);
                        break;
                }
            }

            $jsonString = file_get_contents(FileHandler::pathChangelog(true));
            $decoded_json = json_decode($jsonString);
            if ($decoded_json == null || ! property_exists($decoded_json, 'unreleased')) {
                $this->error('No release changelog exists to update');

                return CommandAlias::FAILURE;
            } else {
                app(Constants::APP_VERISON_HANDLING)->updateVersion($major,$minor,$patch);
                $decoded_json = VersionUtil::generateChangelogWithNewVersion($decoded_json, $name);
                file_put_contents(FileHandler::pathChangelog(), json_encode($decoded_json));
                return self::SUCCESS;
            }

        } catch (\InvalidArgumentException $e) {
            return self::FAILURE;
        } catch (\Exception $e2) {
            $this->error("Error:  $e2 ");

            return self::INVALID;
        }
    }

    private function getArgument(string $key): string
    {
        $result = $this->option($key);

        if (is_null($result)) {
            $result = $this->ask('What is '.$key.' ?');
        }

        if ($result == null) {
            $this->error("No input for key:  $key ");
            throw new \InvalidArgumentException();
        }

        return $result;
    }


}
