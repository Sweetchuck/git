<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class InvokeCustom extends CliCommandBase
{

    /**
     * @return array<string, mixed>
     */
    public function getRawProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setRawProperties(array $properties): static
    {
        $this->properties = $properties;

        return $this;
    }
}
