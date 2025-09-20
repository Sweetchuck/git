<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class SetRemotePushUrl extends SetRemoteFetchUrl
{
    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['commandOptions']['push']['state'] = true;

        return $this;
    }
}
