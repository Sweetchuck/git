<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git remote set-url --push --add" command.
 */
class AddRemotePushUrl extends AddRemoteFetchUrl
{
    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['commandOptions']['push']['state'] = true;

        return $this;
    }
}
