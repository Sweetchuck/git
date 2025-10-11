<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\OutcomeParser\LinesParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git remote get-url --all <remoteName>" command.
 */
class GetRemoteFetchUrls extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'get-url'];
        $this->properties['commandOptions']['all'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['push'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => null,
        ];

        $this->properties['commandArguments']['remoteName'] = null;

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        if (array_key_exists('remoteName', $properties)) {
            $this->setRemoteName($properties['remoteName']);
        }

        return $this;
    }

    public function setRemoteName(string $name): static
    {
        $this->properties['commandArguments']['remoteName'] = $name;

        return $this;
    }

    public function getRemoteName(): ?string
    {
        return $this->properties['commandArguments']['remoteName'];
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new LinesParser();
    }

    protected function getOutputParserOptions(): array
    {
        return [
            'key' => 'urls',
        ];
    }
}
