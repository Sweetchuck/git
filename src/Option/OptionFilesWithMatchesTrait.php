<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFilesWithMatchesTrait
{

    protected function initPropertyFilesWithMatches(): static
    {
        $this->properties['commandOptions']['filesWithMatches'] = [
            'type' => 'state:bool',
            'name' => '--files-with-matches',
            'name-no' => '--files-without-match',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFilesWithMatches(array $properties): static
    {
        if (array_key_exists('filesWithMatches', $properties)) {
            $this->setFilesWithMatches($properties['filesWithMatches']);
        }

        return $this;
    }

    public function getFilesWithMatches(): ?bool
    {
        return $this->properties['commandOptions']['filesWithMatches']['state'];
    }

    public function setFilesWithMatches(?bool $value): static
    {
        $this->properties['commandOptions']['filesWithMatches']['state'] = $value;

        return $this;
    }
}
