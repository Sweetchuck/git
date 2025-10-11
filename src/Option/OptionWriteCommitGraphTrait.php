<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionWriteCommitGraphTrait
{

    protected function initPropertyWriteCommitGraph(): static
    {
        $this->properties['commandOptions']['writeCommitGraph'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyWriteCommitGraph(array $properties): static
    {
        if (array_key_exists('writeCommitGraph', $properties)) {
            $this->setWriteCommitGraph($properties['writeCommitGraph']);
        }

        return $this;
    }

    public function getWriteCommitGraph(): ?bool
    {
        return $this->properties['commandOptions']['writeCommitGraph']['state'];
    }

    public function setWriteCommitGraph(?bool $value): static
    {
        $this->properties['commandOptions']['writeCommitGraph']['state'] = $value;

        return $this;
    }
}
