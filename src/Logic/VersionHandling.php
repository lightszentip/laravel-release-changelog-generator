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

    public function incrementMinor(): void
    {
        $this->yamlContents = VersionCalculator::incrementMinor($this->yamlContents);
        $this->saveYamlContent();
    }

    public function incrementMajor(): void
    {
        $this->yamlContents = VersionCalculator::incrementMajor($this->yamlContents);
        $this->saveYamlContent();
    }

    public function incrementPatch(): void
    {
        $this->yamlContents = VersionCalculator::incrementPatch($this->yamlContents);
        $this->saveYamlContent();
    }

    public function incrementPreRelease(): void
    {
        $this->yamlContents = VersionCalculator::incrementPreRelease($this->yamlContents);
        $this->saveYamlContent();
    }

    public function getContent()
    {
        return $this->yamlContents;
    }

    private function saveYamlContent(): void
    {
        file_put_contents($this->yamlPath, Yaml::dump($this->yamlContents));
    }
}
