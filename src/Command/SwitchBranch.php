<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionConflictTrait;
use Sweetchuck\Git\Option\OptionDetachTrait;
use Sweetchuck\Git\Option\OptionDiscardChangesTrait;
use Sweetchuck\Git\Option\OptionGuessTrait;
use Sweetchuck\Git\Option\OptionIgnoreOtherWorktreesTrait;
use Sweetchuck\Git\Option\OptionMergeTrait;
use Sweetchuck\Git\Option\OptionOrphanTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionTrackTrait;

/**
 * Represents the "git switch" command.
 *
 * @see https://git-scm.com/docs/git-switch
 *
 * @phpstan-import-type SweetchuckGitCommandSwitchBranchProperties from \Sweetchuck\Git\Phpstan
 */
class SwitchBranch extends CliCommandBase
{
    use OptionDetachTrait;
    use OptionGuessTrait;
    use OptionDiscardChangesTrait;
    use OptionMergeTrait;
    use OptionConflictTrait;
    use OptionTrackTrait;
    use OptionOrphanTrait;
    use OptionIgnoreOtherWorktreesTrait;
    use OptionRecurseSubmodulesTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['switch'];
        $this->properties['commandArguments'] = [
            'name' => null,
            'startPoint' => null,
        ];

        $this
            ->initPropertyDetach()
            ->initPropertyGuess()
            ->initPropertyDiscardChanges()
            ->initPropertyMerge()
            ->initPropertyConflict()
            ->initPropertyTrack()
            ->initPropertyOrphan()
            ->initPropertyIgnoreOtherWorktrees()
            ->initPropertyRecurseSubmodules();

        return $this;
    }

    /**
     * @phpstan-param SweetchuckGitCommandSwitchBranchProperties $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyDetach($properties)
            ->setPropertyGuess($properties)
            ->setPropertyDiscardChanges($properties)
            ->setPropertyMerge($properties)
            ->setPropertyConflict($properties)
            ->setPropertyTrack($properties)
            ->setPropertyOrphan($properties)
            ->setPropertyIgnoreOtherWorktrees($properties)
            ->setPropertyRecurseSubmodules($properties);

        if (array_key_exists('createMethod', $properties)) {
            $this->setCreateMethod($properties['createMethod']);
        }

        if (array_key_exists('name', $properties)) {
            $this->setBranchName($properties['name']);
        }

        if (array_key_exists('startPoint', $properties)) {
            $this->setStartPoint($properties['startPoint']);
        }

        return $this;
    }

    protected ?string $createMethod = null;

    /**
     * @param null|"normal"|"force" $createMethod
     */
    public function setCreateMethod(?string $createMethod): static
    {
        $this->createMethod = $createMethod;

        return $this;
    }

    public function getCreateMethod(): ?string
    {
        return $this->createMethod;
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

    /**
     * {@inheritdoc}
     */
    public function getCliCommand(): array
    {
        return $this
            ->preGetCliCommand()
            ->getCliCommandBuilder()
            ->build($this->getFinalProperties());
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFinalProperties(): array
    {
        $properties = parent::getFinalProperties();
        $createMethod = $this->getCreateMethod();
        if ($createMethod !== null) {
            $properties['commandOptions']['create'] = [
                'type' => 'value:string-required',
                'name' => match ($createMethod) {
                    'force' => '--force-create',
                    default => '--create',
                },
                'value' => $properties['commandArguments']['name'],
            ];

            $properties['commandArguments']['name'] = null;
        }

        return $properties;
    }
}
