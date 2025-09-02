<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Commands;

use Illuminate\Console\Command;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\Constants;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\FileHandler;
use Lightszentip\LaravelReleaseChangelogGenerator\Util\VersionUtil;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SetReleaseChangelog extends Command
{
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
    private static string $ar_name = 'releasename';

    private static string $ar_version = 'versionnumber';

    /**
     * Create a new command instance.
     *
     * Initializes the SetReleaseChangelog command by calling the parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command to create a new release version in the changelog.
     *
     * This method processes user input for release name and version number, parses the version
     * into semantic version components (major, minor, patch), reads the existing changelog,
     * validates its structure, updates the version handling system, generates a new changelog
     * with the release version, and saves it back to the file.
     *
     * @return int Command exit status: SUCCESS (0) on successful execution,
     *             FAILURE (1) on invalid argument exception,
     *             INVALID (2) on general exceptions
     */
    public function handle(): int
    {
        try {
            $name = trim($this->getArgument(self::$ar_name));
            $version = trim($this->getArgument(self::$ar_version));

            $versionSplit = explode('.', $version);
            $major = 0;
            $minor = 0;
            $patch = 0;
            foreach ($versionSplit as $i => $iValue) {
                switch ($i) {
                    case 0:
                        $major = $iValue;

                        break;

                    case 1:
                        $minor = $iValue;

                        break;

                    case 2:
                        $patch = preg_replace('~\D~', '', $versionSplit[$i]);

                        break;
                }
            }

            $jsonString = file_get_contents(FileHandler::pathChangelog(true));
            $decoded_json = json_decode($jsonString);
            if (null === $decoded_json || !property_exists($decoded_json, 'unreleased')) {
                $this->error('No release changelog exists to update');

                return CommandAlias::FAILURE;
            }

            app(Constants::APP_VERSION_HANDLING)->updateVersion($major, $minor, $patch);
            $decoded_json = VersionUtil::generateChangelogWithNewVersion($decoded_json, $name);
            file_put_contents(FileHandler::pathChangelog(), json_encode($decoded_json));

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            return self::FAILURE;
        } catch (\Exception $e2) {
            $this->error("Error:  {$e2} ");

            return self::INVALID;
        }
    }

    /**
     * Get command argument value with interactive fallback.
     *
     * Attempts to retrieve the argument value from command options first.
     * If not provided, prompts the user interactively for input.
     * Throws an exception if no value is provided after prompting.
     *
     * @param string $key The argument key to retrieve
     *
     * @return string The argument value provided by user
     *
     * @throws \InvalidArgumentException When no input is provided for the required key
     */
    private function getArgument(string $key): string
    {
        $result = $this->option($key);

        if (null === $result) {
            $result = $this->ask('What is '.$key.' ?');
        }

        if (null === $result) {
            $this->error("No input for key:  {$key} ");

            throw new \InvalidArgumentException();
        }

        return $result;
    }
}
