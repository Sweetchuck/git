<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git remote set-url --add" command.
 */
class AddRemoteFetchUrl extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'set-url'];
        $this->properties['commandOptions']['push'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => null,
        ];
        $this->properties['commandOptions']['add'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['delete'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => null,
        ];

        $this->properties['commandArguments']['remoteName'] = null;
        $this->properties['commandArguments']['url'] = null;

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        if (array_key_exists('remoteName', $properties)) {
            $this->setRemoteName($properties['remoteName']);
        }

        if (array_key_exists('url', $properties)) {
            $this->setUrl($properties['url']);
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

    public function setUrl(string $name): static
    {
        $this->properties['commandArguments']['url'] = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->properties['commandArguments']['url'];
    }
}
