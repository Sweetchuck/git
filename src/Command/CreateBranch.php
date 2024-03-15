<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionForceTrait;

/**
 * Represents the "git branch <branch-name>" command.
 *
 * @see https://git-scm.com/docs/git-branch
 *
 * @phpstan-import-type SweetchuckGitCommandCreateBranchProperties from \Sweetchuck\Git\Phpstan
 */
class CreateBranch extends CliCommandBase
{
    use OptionForceTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['branch'];
        $this->properties['commandArguments'] = [
            'name' => null,
            'startPoint' => null,
        ];

        $this->initPropertyForce();

        return $this;
    }

    /**
     * @phpstan-param SweetchuckGitCommandCreateBranchProperties $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyForce($properties);

        if (array_key_exists('name', $properties)) {
            $this->setBranchName($properties['name']);
        }

        if (array_key_exists('startPoint', $properties)) {
            $this->setStartPoint($properties['startPoint']);
        }

        return $this;
    }

    public function getBranchName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setBranchName(?string $name): static
    {
        $this->properties['commandArguments']['name'] = $name;

        return $this;
    }

    public function getStartPoint(): ?string
    {
        return $this->properties['commandArguments']['startPoint'];
    }

    public function setStartPoint(?string $startPoint): static
    {
        $this->properties['commandArguments']['startPoint'] = $startPoint;

        return $this;
    }
}
