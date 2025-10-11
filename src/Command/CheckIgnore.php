<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionNoIndexTrait;
use Sweetchuck\Git\OutcomeParser\CheckIgnoreParser;
use Sweetchuck\Git\OutcomeParserInterface;

class CheckIgnore extends CliCommandBase
{
    use OptionNoIndexTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['check-ignore'];
        $this->properties['commandOptions']['verbose'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['nonMatching'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];

        $this->initPropertyNoIndex();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyNoIndex($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new CheckIgnoreParser();
    }
}
