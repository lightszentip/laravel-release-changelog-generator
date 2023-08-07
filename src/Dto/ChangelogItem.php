<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Dto;

class ChangelogItem
{
    private string $issue;

    private string $message;

    /**
     * @param string $message
     * @param string|null $issue
     */
    public function __construct(string $message, string $issue = null)
    {
        $this->issue = $issue;
        $this->message = $message;
    }

    /**
     * build result
     *
     * @return array|string[]
     */
    public function build(): array
    {
        if ($this->issue == null) {
            return ['message' => $this->message];
        }

        return ['message' => $this->message, 'issue' => $this->issue];
    }
}
