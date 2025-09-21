<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git remote set-url <remoteName> <url> [oldUrl]" command.
 */
class SetRemoteFetchUrl extends AddRemoteFetchUrl
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['commandOptions']['add']['state'] = null;
        $this->properties['commandOptions']['delete']['state'] = null;

        $this->properties['commandArguments']['oldUrl'] = null;

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        if (array_key_exists('oldUrl', $properties)) {
            $this->setOldUrl($properties['oldUrl']);
        }

        return $this;
    }

    public function getOldUrl(): ?string
    {
        return $this->properties['commandArguments']['oldUrl'];
    }

    public function setOldUrl(?string $oldUrl): static
    {
        $this->properties['commandArguments']['oldUrl'] = $oldUrl;

        return $this;
    }
}
