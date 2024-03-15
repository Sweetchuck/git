<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSeparateGitDirTrait
{

    protected function initPropertySeparateGitDir(): static
    {
        $this->properties['commandOptions']['separateGitDir'] = [
            'type' => 'value:false:string-required',
            'name' => '--separate-git-dir',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySeparateGitDir(array $properties): static
    {
        if (array_key_exists('separateGitDir', $properties)) {
            $this->setSeparateGitDir($properties['separateGitDir']);
        }

        return $this;
    }

    public function getSeparateGitDir(): null|false|string
    {
        return $this->properties['commandOptions']['separateGitDir']['value'];
    }

    public function setSeparateGitDir(null|false|string $value): static
    {
        $this->properties['commandOptions']['separateGitDir']['value'] = $value;

        return $this;
    }
}
