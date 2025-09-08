<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionConflictTrait;
use Sweetchuck\Git\Option\OptionIgnoreSkipWorktreeBitsTrait;
use Sweetchuck\Git\Option\OptionIgnoreUnmergedTrait;
use Sweetchuck\Git\Option\OptionMergeTrait;
use Sweetchuck\Git\Option\OptionOverlayTrait;
use Sweetchuck\Git\Option\OptionPathSpecFromFileTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionSourceTrait;
use Sweetchuck\Git\Option\OptionStagedTrait;
use Sweetchuck\Git\Option\OptionUnifiedTrait;
use Sweetchuck\Git\Option\OptionWorkingTreeTrait;

/**
 * Represents the "git restore" command.
 *
 * @see https://git-scm.com/docs/git-restore
 *
 * @todo Option --ours.
 * @todo Option --theirs.
 * @todo Option --[no-]patch.
 * @todo Option --inter-hunk-context <n>.
 *
 * @phpstan-import-type SweetchuckGitCommandRestoreFilesProperties from \Sweetchuck\Git\Phpstan
 */
class RestoreFiles extends CliCommandBase
{
    use OptionSourceTrait;
    use OptionStagedTrait;
    use OptionWorkingTreeTrait;
    use OptionIgnoreUnmergedTrait;
    use OptionOverlayTrait;
    use OptionRecurseSubmodulesTrait;
    use OptionMergeTrait;
    use OptionConflictTrait;
    use OptionUnifiedTrait;
    use OptionIgnoreSkipWorktreeBitsTrait;
    use OptionPathSpecFromFileTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['restore'];
        $this
            ->initPropertySource()
            ->initPropertyStaged()
            ->initPropertyWorkingTree()
            ->initPropertyIgnoreUnmerged()
            ->initPropertyOverlay()
            ->initPropertyRecurseSubmodules()
            ->initPropertyMerge()
            ->initPropertyConflict()
            ->initPropertyUnified()
            ->initPropertyIgnoreSkipWorktreeBits()
            ->initPropertyPathSpecFromFile();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertySource($properties)
            ->setPropertyStaged($properties)
            ->setPropertyWorkingTree($properties)
            ->setPropertyIgnoreUnmerged($properties)
            ->setPropertyOverlay($properties)
            ->setPropertyRecurseSubmodules($properties)
            ->setPropertyMerge($properties)
            ->setPropertyConflict($properties)
            ->setPropertyUnified($properties)
            ->setPropertyIgnoreSkipWorktreeBits($properties)
            ->setPropertyPathSpecFromFile($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }
}
