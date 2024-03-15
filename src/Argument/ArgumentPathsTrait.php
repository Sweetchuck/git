<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Argument;

trait ArgumentPathsTrait
{
    /**
     * @var array<string, boolean>
     */
    protected array $paths = [];

    /**
     * @return array<string, bool>
     */
    public function getPaths(): array
    {
        return $this->paths;
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
        $this->paths = $paths;

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
            $this->paths[$path] = $status;
        }

        return $this;
    }

    public function addPath(string $path): static
    {
        $this->paths[$path] = true;

        return $this;
    }

    public function removePath(string $value): static
    {
        $this->paths[$value] = false;

        return $this;
    }
}
