<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Argument;

/**
 * @property array<string, mixed> $properties
 */
trait ArgumentNamesTrait
{
    /**
     * @return array<string, bool>
     */
    public function getNames(): array
    {
        return $this->properties['extraArguments'];
    }

    /**
     * @param array<string>|array<string, bool> $names
     */
    public function setNames(array $names): static
    {
        if (gettype(reset($names)) !== 'boolean') {
            $names = array_fill_keys($names, true);
        }

        /** @var array<string, bool> $names */
        $this->properties['extraArguments'] = $names;

        return $this;
    }

    /**
     * @param array<string>|array<string, bool> $names
     */
    public function updateNames(array $names, bool $default = true): static
    {
        if (gettype(reset($names)) !== 'boolean') {
            $names = array_fill_keys($names, $default);
        }

        /** @var array<string, bool> $names */
        foreach ($names as $name => $status) {
            $this->properties['extraArguments'][$name] = $status;
        }

        return $this;
    }

    public function addName(string $name): static
    {
        $this->properties['extraArguments'][$name] = true;

        return $this;
    }

    public function removeName(string $name): static
    {
        unset($this->properties['extraArguments'][$name]);

        return $this;
    }
}
