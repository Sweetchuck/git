<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class DeleteRemotePushUrl extends AddRemotePushUrl
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['commandOptions']['add']['state'] = null;
        $this->properties['commandOptions']['delete']['state'] = true;

        return $this;
    }
}
