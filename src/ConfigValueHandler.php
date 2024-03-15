<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

class ConfigValueHandler implements ConfigValueHandlerInterface
{

    public function transform(string $name, string $value): mixed
    {
        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return $value;
    }
}
