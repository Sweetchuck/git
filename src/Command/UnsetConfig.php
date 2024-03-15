<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionConfigScopeTrait;
use Sweetchuck\Git\Option\OptionFileTrait;
use Sweetchuck\Git\Option\OptionFixedValueTrait;

/**
 * Represents the "git config unset" command.
 */
class UnsetConfig extends CliCommandBase
{
    use OptionConfigScopeTrait;
    use OptionFileTrait;
    use OptionAllTrait;
    use OptionFixedValueTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = [
            'config',
            'unset',
        ];

        $this
            ->initPropertyConfigScope()
            ->initPropertyFile()
            ->initPropertyAll();

        $this->properties['commandOptions']['value'] = [
            'type' => 'state:bool:string-required',
            'state' => null,
            'value' => null,
        ];

        $this->initPropertyFixedValue();

        $this->properties['commandArguments'] = [
            'name' => '',
            'value' => '',
        ];

        return $this;
    }

    public function getConfigName(): string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setConfigName(string $name): static
    {
        $this->properties['commandArguments']['name'] = $name;

        return $this;
    }

    public function getValueState(): null|bool
    {
        return $this->properties['commandOptions']['value']['state'];
    }

    public function setValueState(null|bool $state): static
    {
        $this->properties['commandOptions']['value']['state'] = $state;

        return $this;
    }

    public function getValuePattern(): null|string
    {
        return $this->properties['commandOptions']['value']['value'];
    }

    public function setValuePattern(null|string $pattern): static
    {
        $this->properties['commandOptions']['value']['value'] = $pattern;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyConfigScope($properties);
        $this->setPropertyFile($properties);
        $this->setPropertyAll($properties);
        $this->setPropertyFixedValue($properties);

        if (array_key_exists('configName', $properties)) {
            $this->setConfigName($properties['configName']);
        }

        if (array_key_exists('valueState', $properties)) {
            $this->setValueState($properties['valueState']);
        }

        if (array_key_exists('valuePattern', $properties)) {
            $this->setValuePattern($properties['valuePattern']);
        }

        return $this;
    }

    protected function preGetCliCommand(): static
    {
        $this->properties['commandArguments'] = [
            'name' => $this->getConfigName(),
        ];

        return $this;
    }
}
