<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Yaml\Yaml;

class VersionHandling
{
    /**
     * The parsed YAML contents from the version file.
     *
     * @var mixed
     */
    protected mixed $yamlContents;

    /**
     * The file path to the version.yml file.
     *
     * @var string
     */
    protected string $yamlPath;

    /**
     * Initialize the VersionHandling instance by loading the version YAML file.
     *
     * Constructs the path to the version.yml file using the configured version path
     * and loads its contents into memory for manipulation.
     */
    public function __construct()
    {
        $this->yamlPath = Config::get('releasechangelog.version_path') . DIRECTORY_SEPARATOR . '.version' . DIRECTORY_SEPARATOR . 'version.yml';
        $this->yamlContents = Yaml::parse(file_get_contents($this->yamlPath));
    }

    /**
     * Increment the minor version number and save the changes to the YAML file.
     *
     * Uses the VersionCalculator to increment the minor version in the YAML contents
     * and automatically saves the updated version with a new timestamp.
     *
     * @return void
     */
    public function incrementMinor(): void
    {
        $this->yamlContents = VersionCalculator::incrementMinor($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * Increment the major version number and save the changes to the YAML file.
     *
     * Uses the VersionCalculator to increment the major version in the YAML contents
     * and automatically saves the updated version with a new timestamp.
     *
     * @return void
     */
    public function incrementMajor(): void
    {
        $this->yamlContents = VersionCalculator::incrementMajor($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * Increment the patch version number and save the changes to the YAML file.
     *
     * Uses the VersionCalculator to increment the patch version in the YAML contents
     * and automatically saves the updated version with a new timestamp.
     *
     * @return void
     */
    public function incrementPatch(): void
    {
        $this->yamlContents = VersionCalculator::incrementPatch($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * Increment the pre-release version number and save the changes to the YAML file.
     *
     * Uses the VersionCalculator to increment the pre-release version in the YAML contents
     * and automatically saves the updated version with a new timestamp.
     *
     * @return void
     */
    public function incrementPreRelease(): void
    {
        $this->yamlContents = VersionCalculator::incrementPreRelease($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * Update the version to specific major, minor, and patch numbers and save the changes.
     *
     * Uses the VersionCalculator to set the version to the specified values in the YAML contents
     * and automatically saves the updated version with a new timestamp.
     *
     * @param int $major The major version number to set
     * @param int $minor The minor version number to set
     * @param int $patch The patch version number to set
     * @return void
     */
    public function updateVersion(int $major, int $minor, int $patch): void
    {
        $this->yamlContents = VersionCalculator::updateVersion($this->yamlContents, $major, $minor, $patch);
        $this->saveYamlContent();
    }

    /**
     * Update only the timestamp in the version file without changing version numbers.
     *
     * Saves the current YAML contents to file, which automatically updates the timestamp
     * to the current time without modifying any version numbers.
     *
     * @return void
     */
    public function updateTimestamp(): void
    {
        $this->saveYamlContent();
    }

    /**
     * Get the current contents of the version YAML file.
     *
     * Returns the parsed YAML contents that are currently loaded in memory,
     * which may include any unsaved modifications.
     *
     * @return mixed The parsed YAML contents from the version file
     */
    public function getContent(): mixed
    {
        return $this->yamlContents;
    }

    /**
     * Save the current YAML contents to the version file with updated timestamp.
     *
     * Updates the timestamp fields in the YAML contents with the current time
     * and date, then writes the entire contents back to the version.yml file.
     *
     * @return void
     */
    private function saveYamlContent(): void
    {
        $this->yamlContents['timestamp']['timestamp'] = time();
        $this->yamlContents['timestamp']['date'] = date("Y-m-d H:i");
        file_put_contents($this->yamlPath, Yaml::dump($this->yamlContents));
    }
}
