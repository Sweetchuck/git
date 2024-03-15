<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Argument;

/**
 * @property array<string, mixed> $properties
 */
trait ArgumentPathsTrait
{
    /**
     * @return array<string, bool>
     */
    public function getPaths(): array
    {
        return $this->properties['extraArguments'];
    }

    /**
     * @param array<string>|array<string, bool> $paths
     */
    public function setPaths(array $paths): static
    {
        if (gettype(reset($paths)) !== 'boolean') {
            $paths = array_fill_keys($paths, true);
        }

        /** @var array<string, bool> $paths */
        $this->properties['extraArguments'] = $paths;

        return $this;
    }

    /**
     * @param array<string>|array<string, bool> $paths
     */
    public function updatePaths(array $paths, bool $default = true): static
    {
        if (gettype(reset($paths)) !== 'boolean') {
            $paths = array_fill_keys($paths, $default);
        }

        /** @var array<string, bool> $paths */
        foreach ($paths as $path => $status) {
            $this->properties['extraArguments'][$path] = $status;
        }

        return $this;
    }

    public function addPath(string $path): static
    {
        $this->properties['extraArguments'][$path] = true;

        return $this;
    }

    public function removePath(string $value): static
    {
        unset($this->properties['extraArguments'][$value]);

        return $this;
    }
}
