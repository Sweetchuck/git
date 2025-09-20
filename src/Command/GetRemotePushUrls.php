<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class GetRemotePushUrls extends GetRemoteFetchUrls
{
    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['commandOptions']['push']['state'] = true;

        return $this;
    }
}
