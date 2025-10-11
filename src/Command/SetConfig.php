<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionConfigScopeTrait;
use Sweetchuck\Git\Option\OptionFileTrait;

/**
 * Represents the "git config set" command.
 */
class SetConfig extends CliCommandBase
{
    use OptionConfigScopeTrait;
    use OptionFileTrait;
    use OptionAllTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = [
            'config',
            'set',
        ];

        $this
            ->initPropertyConfigScope()
            ->initPropertyFile()
            ->initPropertyAll();

        $this->properties['commandOptions']['append'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => null,
        ];
        $this->properties['commandOptions']['type'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::ValueStringRequired,
            'value' => null,
        ];
        $this->properties['commandOptions']['comment'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

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

    public function getConfigValue(): null|bool|int|float|string
    {
        return $this->properties['commandArguments']['value'];
    }

    public function setConfigValue(null|bool|int|float|string $value): static
    {
        $this->properties['commandArguments']['value'] = $value;

        return $this;
    }

    public function getAppend(): ?bool
    {
        return $this->properties['commandOptions']['append']['state'];
    }

    /**
     * Set the append option state.
     */
    public function setAppend(?bool $state): static
    {
        $this->properties['commandOptions']['append']['state'] = $state;

        return $this;
    }

    /**
     * Get the type option value.
     */
    public function getType(): ?string
    {
        return $this->properties['commandOptions']['type']['value'];
    }

    /**
     * @param null|string $type
     *   Possible values:
     *   - bool
     *   - int
     *   - bool-or-int
     *   - path
     *   - expiry-date
     *   - color
     */
    public function setType(?string $type): static
    {
        $this->properties['commandOptions']['type']['value'] = $type;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->properties['commandOptions']['comment']['value'];
    }

    public function setComment(?string $comment): static
    {
        $this->properties['commandOptions']['comment']['value'] = $comment;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyConfigScope($properties);
        $this->setPropertyAll($properties);

        if (array_key_exists('file', $properties)) {
            $this->setFile($properties['file']);
        }

        if (array_key_exists('configName', $properties)) {
            $this->setConfigName($properties['configName']);
        }

        if (array_key_exists('configValue', $properties)) {
            $this->setConfigValue($properties['configValue']);
        }

        if (array_key_exists('all', $properties)) {
            $this->setAll($properties['all']);
        }

        if (array_key_exists('append', $properties)) {
            $this->setAppend($properties['append']);
        }

        if (array_key_exists('type', $properties)) {
            $this->setType($properties['type']);
        }

        if (array_key_exists('comment', $properties)) {
            $this->setComment($properties['comment']);
        }

        return $this;
    }

    protected function preGetCliCommand(): static
    {
        $value = $this->getConfigValue();
        $type = $this->getType();
        if ($type === null) {
            switch (gettype($value)) {
                case 'boolean':
                    $this->setType('bool');
                    $value = $value ? 'true' : 'false';
                    break;

                case 'integer':
                    $this->setType('int');
                    break;
            }
        }

        $this->properties['commandArguments'] = [
            'name' => $this->getConfigName(),
            'value' => $value,
        ];

        return $this;
    }
}
