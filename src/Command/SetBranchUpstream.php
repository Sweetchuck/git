<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git branch --set-upstream-to" command.
 *
 * @see https://git-scm.com/docs/git-branch
 *
 * @phpstan-import-type SweetchuckGitCommandSetBranchUpstreamProperties from \Sweetchuck\Git\Phpstan
 */
class SetBranchUpstream extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['branch'];
        $this->properties['commandOptions']['setUpstreamTo'] = [
            'type' => 'value:string-required',
            'name' => '--set-upstream-to',
            'value' => null,
        ];
        $this->properties['commandArguments'] = [
            'branch' => null,
        ];

        return $this;
    }

    /**
     * @phpstan-param SweetchuckGitCommandSetBranchUpstreamProperties $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('upstream', $properties)) {
            $this->setUpstream($properties['upstream']);
        }

        if (array_key_exists('branch', $properties)) {
            $this->setBranch($properties['branch']);
        }

        return $this;
    }

    public function getUpstream(): ?string
    {
        return $this->properties['commandOptions']['setUpstreamTo']['value'] ?? null;
    }

    public function setUpstream(?string $upstream): static
    {
        $this->properties['commandOptions']['setUpstreamTo']['value'] = $upstream;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->properties['commandArguments']['branch'];
    }

    public function setBranch(?string $branch): static
    {
        $this->properties['commandArguments']['branch'] = $branch;

        return $this;
    }
}
