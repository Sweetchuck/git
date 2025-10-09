<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionCachedTrait;
use Sweetchuck\Git\Option\OptionDeletedTrait;
use Sweetchuck\Git\Option\OptionDirectoryTrait;
use Sweetchuck\Git\Option\OptionErrorUnmatchTrait;
use Sweetchuck\Git\Option\OptionExcludeFromTrait;
use Sweetchuck\Git\Option\OptionExcludePerDirectoryTrait;
use Sweetchuck\Git\Option\OptionExcludeStandardTrait;
use Sweetchuck\Git\Option\OptionExcludeTrait;
use Sweetchuck\Git\Option\OptionIgnoredTrait;
use Sweetchuck\Git\Option\OptionKilledTrait;
use Sweetchuck\Git\Option\OptionModifiedTrait;
use Sweetchuck\Git\Option\OptionNoEmptyDirectoryTrait;
use Sweetchuck\Git\Option\OptionOthersTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionResolveUndoTrait;
use Sweetchuck\Git\Option\OptionSparseTrait;
use Sweetchuck\Git\Option\OptionStageTrait;
use Sweetchuck\Git\Option\OptionUnmergedTrait;
use Sweetchuck\Git\Option\OptionWithTreeTrait;
use Sweetchuck\Git\OutcomeParser\GetFilesInWorkingCopyParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git ls-files" command.
 *
 * @todo Use "--format".
 */
class GetFilesInWorkingCopy extends CliCommandBase
{

    use OptionCachedTrait;
    use OptionDeletedTrait;
    use OptionModifiedTrait;
    use OptionOthersTrait;
    use OptionIgnoredTrait;
    use OptionStageTrait;
    use OptionDirectoryTrait;
    use OptionNoEmptyDirectoryTrait;
    use OptionUnmergedTrait;
    use OptionKilledTrait;
    use OptionResolveUndoTrait;
    use OptionExcludeStandardTrait;
    use OptionErrorUnmatchTrait;
    use OptionRecurseSubmodulesTrait;
    use OptionSparseTrait;
    use OptionExcludeTrait;
    use OptionExcludeFromTrait;
    use OptionExcludePerDirectoryTrait;
    use OptionWithTreeTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['ls-files'];

        $this->properties['commandOptions']['-z'] = [
            'type' => 'state:true',
            'name' => '-z',
            'state' => true,
        ];
        $this->properties['commandOptions']['-t'] = [
            'type' => 'state:true',
            'name' => '-t',
            'state' => true,
        ];
        $this->properties['commandOptions']['eol'] = [
            'type' => 'state:true',
            'state' => true,
        ];

        $this
            ->initPropertyCached()
            ->initPropertyDeleted()
            ->initPropertyModified()
            ->initPropertyOthers()
            ->initPropertyIgnored()
            ->initPropertyStage()
            ->initPropertyDirectory()
            ->initPropertyNoEmptyDirectory()
            ->initPropertyUnmerged()
            ->initPropertyKilled()
            ->initPropertyResolveUndo()
            ->initPropertyExcludeStandard()
            ->initPropertyErrorUnmatch()
            ->initPropertyRecurseSubmodules()
            ->initPropertySparse()
            ->initPropertyExclude()
            ->initPropertyExcludeFrom()
            ->initPropertyExcludePerDirectory()
            ->initPropertyWithTree();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyCached($properties)
            ->setPropertyDeleted($properties)
            ->setPropertyModified($properties)
            ->setPropertyOthers($properties)
            ->setPropertyIgnored($properties)
            ->setPropertyStage($properties)
            ->setPropertyDirectory($properties)
            ->setPropertyNoEmptyDirectory($properties)
            ->setPropertyUnmerged($properties)
            ->setPropertyKilled($properties)
            ->setPropertyResolveUndo($properties)
            ->setPropertyExcludeStandard($properties)
            ->setPropertyErrorUnmatch($properties)
            ->setPropertyRecurseSubmodules($properties)
            ->setPropertySparse($properties)
            ->setPropertyExclude($properties)
            ->setPropertyExcludeFrom($properties)
            ->setPropertyExcludePerDirectory($properties)
            ->setPropertyWithTree($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetFilesInWorkingCopyParser();
    }
}
