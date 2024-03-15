<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 * @property \Sweetchuck\Git\Utils $utils
 */
trait OptionConfigScopeTrait
{

    protected function initPropertyConfigScope(): static
    {
        $this->properties['commandOptions']['global'] = [
            'type' => 'state:bool',
            'state' => null,
        ];
        $this->properties['commandOptions']['system'] = [
            'type' => 'state:bool',
            'state' => null,
        ];
        $this->properties['commandOptions']['local'] = [
            'type' => 'state:bool',
            'state' => null,
        ];
        $this->properties['commandOptions']['worktree'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyConfigScope(array $properties): static
    {
        if (array_key_exists('configScope', $properties)) {
            $this->setConfigScope($properties['configScope']);
        }

        return $this;
    }

    /**
     * @return array<string, ?bool>
     */
    public function getConfigScope(): array
    {
        return [
            'global' => $this->properties['commandOptions']['global']['state'],
            'system' => $this->properties['commandOptions']['system']['state'],
            'local' => $this->properties['commandOptions']['local']['state'],
            'worktree' => $this->properties['commandOptions']['worktree']['state'],
        ];
    }

    /**
     * @param array<string, ?bool> $scopes
     */
    public function setConfigScope(array $scopes): static
    {
        $scopes += [
            'global' => null,
            'system' => null,
            'local' => null,
            'worktree' => null,
        ];
        $this->properties['commandOptions']['global']['state'] = $scopes['global'];
        $this->properties['commandOptions']['system']['state'] = $scopes['system'];
        $this->properties['commandOptions']['local']['state'] = $scopes['local'];
        $this->properties['commandOptions']['worktree']['state'] = $scopes['worktree'];

        return $this;
    }
}
