<?php

namespace Lightszentip\LaravelReleaseChangelogGenerator\Dto;

class ChangelogItem
{
    private string $issue;

    private string $message;


    /**
     * Creates a new changelog item with a message and optional issue reference.
     *
     * @param string      $message The changelog message or description
     * @param string|null $issue   Optional issue number or reference identifier
     */
    public function __construct(string $message, ?string $issue = null)
    {
        $this->issue = $issue;
        $this->message = $message;
    }

    /**
     * build result.
     *
     * @return array|string[]
     */
    public function build(): array
    {
        $result = ['message' => $this->message];

        if (trim($this->issue) !== '') {
            $result['issue'] = $this->issue;
        }

        return $result;
    }
}
