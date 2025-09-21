<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionRecurseTrait;
use Sweetchuck\Git\OutcomeParser\ReadSymbolicRefParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git symbolic-ref <name>" command.
 */
class ReadSymbolicRef extends CliCommandBase
{
    use OptionRecurseTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['symbolic-ref'];
        $this->properties['commandArguments']['name'] = null;

        $this->initPropertyRecurse();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyRecurse($properties);

        if (array_key_exists('name', $properties)) {
            $this->setName($properties['name']);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setName(string $name): static
    {
        $this->properties['commandArguments']['name'] = $name;

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new ReadSymbolicRefParser();
    }
}
