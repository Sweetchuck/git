<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git remote set-url --push" command.
 */
class SetRemotePushUrl extends SetRemoteFetchUrl
{
    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['commandOptions']['push']['state'] = true;

        return $this;
    }
}
