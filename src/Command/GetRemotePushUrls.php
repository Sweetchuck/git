<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git remote get-url --push --all <remoteName>" command.
 */
class GetRemotePushUrls extends GetRemoteFetchUrls
{
    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['commandOptions']['push']['state'] = true;

        return $this;
    }
}
