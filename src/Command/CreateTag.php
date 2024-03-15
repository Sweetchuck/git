<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAnnotateTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionLocalUserTrait;
use Sweetchuck\Git\Option\OptionMessageTrait;
use Sweetchuck\Git\Option\OptionNoSignTrait;
use Sweetchuck\Git\Option\OptionSignTrait;

/**
 * Represents the "git tag" command for creating tags.
 */
class CreateTag extends CliCommandBase
{
    use OptionAnnotateTrait;
    use OptionForceTrait;
    use OptionLocalUserTrait;
    use OptionMessageTrait;
    use OptionNoSignTrait;
    use OptionSignTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['tag'];
        $this->properties['commandArguments']['name'] = null;
        $this
            ->initPropertyAnnotate()
            ->initPropertyForce()
            ->initPropertyLocalUser()
            ->initPropertyMessage()
            ->initPropertyNoSign()
            ->initPropertySign();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('annotate', $properties)) {
            $this->setAnnotate($properties['annotate']);
        }

        if (array_key_exists('force', $properties)) {
            $this->setForce($properties['force']);
        }

        if (array_key_exists('localUser', $properties)) {
            $this->setLocalUser($properties['localUser']);
        }

        if (array_key_exists('message', $properties)) {
            $this->setMessage($properties['message']);
        }

        if (array_key_exists('noSign', $properties)) {
            $this->setNoSign($properties['noSign']);
        }

        if (array_key_exists('sign', $properties)) {
            $this->setSign($properties['sign']);
        }

        if (array_key_exists('name', $properties)) {
            $this->setTagName($properties['name']);
        }

        return $this;
    }

    /**
     * Set the tag name.
     */
    public function setTagName(string $name): static
    {
        $this->properties['commandArguments']['name'] = $name;

        return $this;
    }

    /**
     * Get the tag name.
     */
    public function getTagName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }
}
