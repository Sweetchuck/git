<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

interface ConfigValueHandlerInterface
{
    public function transform(string $name, string $value): mixed;
}
