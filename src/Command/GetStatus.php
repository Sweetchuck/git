<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\OutcomeParser\GetStatusParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git status" command.
 *
 * @phpstan-import-type SweetchuckGitIgnoredModes from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitUntrackedFilesModes from \Sweetchuck\Git\Phpstan
 */
class GetStatus extends CliCommandBase
{
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['status'];
        $this->properties['commandOptions']['porcelain'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['NUL'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'name' => '-z',
            'state' => true,
        ];
        $this->properties['commandOptions']['renames'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => null,
        ];
        $this->properties['commandOptions']['find-renames'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::ValueStringOptional,
            'value' => null,
        ];
        $this->properties['commandOptions']['ignored'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::ValueStringOptional,
            'value' => null,
        ];
        $this->properties['commandOptions']['untracked-files'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::ValueStringOptional,
            'value' => null,
        ];
        $this->properties['commandOptions']['ignore-submodules'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    // region renames
    public function getRenames(): ?bool
    {
        return $this->properties['commandOptions']['renames']['state'];
    }

    public function setRenames(?bool $renames): static
    {
        $this->properties['commandOptions']['renames']['state'] = $renames;

        return $this;
    }
    // endregion

    // region findRenames
    public function getFindRenames(): ?int
    {
        return $this->properties['commandOptions']['find-renames']['value'] === null
            ? null
            : (int) $this->properties['commandOptions']['find-renames']['value'];
    }

    /**
     * @todo PhpStan range int<0, 100>.
     */
    public function setFindRenames(?int $findRenames): static
    {
        if ($findRenames === null) {
            $this->properties['commandOptions']['find-renames']['value'] = null;

            return $this;
        }

        $this->properties['commandOptions']['find-renames']['value'] = $findRenames === 0
            ? ''
            : (string) $findRenames;

        return $this;
    }
    // endregion

    // region ignored
    /**
     * @phpstan-return null|SweetchuckGitIgnoredModes
     */
    public function getIgnored(): ?string
    {
        return $this->properties['commandOptions']['ignored']['value'];
    }

    /**
     * @phpstan-param null|SweetchuckGitIgnoredModes $ignored
     */
    public function setIgnored(?string $ignored): static
    {
        $this->properties['commandOptions']['ignored']['value'] = $ignored;

        return $this;
    }
    // endregion

    // region untrackedFiles
    /**
     * @phpstan-return null|SweetchuckGitUntrackedFilesModes
     */
    public function getUntrackedFiles(): ?string
    {
        return $this->properties['commandOptions']['untracked-files']['value'];
    }

    /**
     * @phpstan-param null|SweetchuckGitUntrackedFilesModes $untrackedFiles
     */
    public function setUntrackedFiles(?string $untrackedFiles): static
    {
        $this->properties['commandOptions']['untracked-files']['value'] = $untrackedFiles;

        return $this;
    }
    // endregion

    // region ignoreSubmodules
    public function getIgnoreSubmodules(): ?bool
    {
        return $this->properties['commandOptions']['ignore-submodules']['state'];
    }

    public function setIgnoreSubmodules(?bool $value): static
    {
        $this->properties['commandOptions']['ignore-submodules']['state'] = $value;

        return $this;
    }
    // endregion

    /**
     * {@inheritdoc}
     *
     * @todo Phpstan type.
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('renames', $properties)) {
            $this->setRenames($properties['renames']);
        }

        if (array_key_exists('findRenames', $properties)) {
            $this->setFindRenames($properties['findRenames']);
        }

        if (array_key_exists('ignored', $properties)) {
            $this->setIgnored($properties['ignored']);
        }

        if (array_key_exists('untrackedFiles', $properties)) {
            $this->setUntrackedFiles($properties['untrackedFiles']);
        }

        if (array_key_exists('ignoreSubmodules', $properties)) {
            $this->setIgnoreSubmodules($properties['ignoreSubmodules']);
        }

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetStatusParser();
    }
}
