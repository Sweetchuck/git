<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionCachedTrait;
use Sweetchuck\Git\Option\OptionSourceTrait;
use Sweetchuck\Git\OutcomeParser\CheckAttrParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git check-attr" command.
 */
class CheckAttr extends CliCommandBase
{
    use OptionCachedTrait;
    use OptionSourceTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['check-attr'];
        $this->properties['commandOptions']['null'] = [
            'type' => 'state:bool',
            'name' => '-z',
            'state' => true,
        ];
        $this->properties['commandOptions']['all'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        $this
            ->initPropertyCached()
            ->initPropertySource();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyCached($properties)
            ->setPropertySource($properties);

        if (array_key_exists('attributes', $properties)) {
            $this->setAttributes($properties['attributes']);
        }

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new CheckAttrParser();
    }

    /**
     * @return array<string, bool>
     */
    public function getAttributes(): array
    {
        return $this->properties['commandArguments'];
    }

    /**
     * @param array<string>|array<string, bool> $paths
     */
    public function setAttributes(array $paths): static
    {
        if (gettype(reset($paths)) !== 'boolean') {
            $paths = array_fill_keys($paths, true);
        }

        /** @var array<string, bool> $paths */
        $this->properties['commandArguments'] = $paths;

        return $this;
    }

    /**
     * @param array<string>|array<string, bool> $paths
     */
    public function updateAttributes(array $paths, bool $default = true): static
    {
        if (gettype(reset($paths)) !== 'boolean') {
            $paths = array_fill_keys($paths, $default);
        }

        /** @var array<string, bool> $paths */
        foreach ($paths as $path => $status) {
            $this->properties['commandArguments'][$path] = $status;
        }

        return $this;
    }

    public function addAttribute(string $path): static
    {
        $this->properties['commandArguments'][$path] = true;

        return $this;
    }

    public function removeAttribute(string $value): static
    {
        unset($this->properties['commandArguments'][$value]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFinalProperties(): array
    {
        $properties = parent::getFinalProperties();
        // @todo Unexpected surprise when every commandArguments is false.
        $properties['commandOptions']['all']['state'] = count($properties['commandArguments']) === 0 ? true : null;

        return $properties;
    }
}
