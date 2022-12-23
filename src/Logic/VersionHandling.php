<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Logic;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Yaml\Yaml;

class VersionHandling
{
    protected mixed $yamlContents;

    protected string $yamlPath;

    public function __construct(

    ) {
        $this->yamlPath = Config::get('releasechangelog.version_path').DIRECTORY_SEPARATOR.'.version'.DIRECTORY_SEPARATOR.'version.yml';
        $this->yamlContents = Yaml::parse(file_get_contents($this->yamlPath));
    }

    /**
     * increment minor and save
     * @return void
     */
    public function incrementMinor(): void
    {
        $this->yamlContents = VersionCalculator::incrementMinor($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * increment major and save
     * @return void
     */
    public function incrementMajor(): void
    {
        $this->yamlContents = VersionCalculator::incrementMajor($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * increment patch and save
     * @return void
     */
    public function incrementPatch(): void
    {
        $this->yamlContents = VersionCalculator::incrementPatch($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * increment pre release and save
     * @return void
     */
    public function incrementPreRelease(): void
    {
        $this->yamlContents = VersionCalculator::incrementPreRelease($this->yamlContents);
        $this->saveYamlContent();
    }

    /**
     * update timestamp only
     * @return void
     */
    public function updateTimestamp(): void
    {
        $this->saveYamlContent();
    }

    /**
     * Get content of version file
     * @return mixed
     */
    public function getContent()
    {
        return $this->yamlContents;
    }

    /**
     * save current content in file
     * @return void
     */
    private function saveYamlContent(): void
    {
        $this->yamlContents['timestamp']['timestamp'] = time();
        $this->yamlContents['timestamp']['date'] = date("Y-m-d H:i");
        file_put_contents($this->yamlPath, Yaml::dump($this->yamlContents));
    }
}
