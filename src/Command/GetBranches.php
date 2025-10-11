<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionColorTrait;
use Sweetchuck\Git\Option\OptionContainsTrait;
use Sweetchuck\Git\Option\OptionFormatTrait;
use Sweetchuck\Git\Option\OptionIgnoreCaseTrait;
use Sweetchuck\Git\Option\OptionMergedTrait;
use Sweetchuck\Git\Option\OptionPointsAtTrait;
use Sweetchuck\Git\Option\OptionRemotesTrait;
use Sweetchuck\Git\Option\OptionSortTrait;
use Sweetchuck\Git\OutcomeParser\GetBranchesParser;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Utils;

/**
 * Represents the "git branch --verbose" command.
 *
 * @phpstan-import-type SweetchuckGitIgnoredModes from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitUntrackedFilesModes from \Sweetchuck\Git\Phpstan
 */
class GetBranches extends CliCommandBase
{
    use OptionColorTrait;
    use OptionAllTrait;
    use OptionContainsTrait;
    use OptionMergedTrait;
    use OptionPointsAtTrait;
    use OptionRemotesTrait;
    use OptionSortTrait;
    use OptionIgnoreCaseTrait;
    use OptionFormatTrait;
    use ArgumentPathsTrait;

    protected Utils $utils;

    public function __construct()
    {
        $this->utils = new Utils();
        parent::__construct();
    }

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['branch'];
        $this->properties['commandOptions']['list'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this
            ->initPropertyColor()
            ->initPropertyMerged()
            ->initPropertyContains()
            ->initPropertyPointsAt()
            ->initPropertyRemotes()
            ->initPropertyAll()
            ->initPropertySort()
            ->initPropertyIgnoreCase()
            ->initPropertyFormat();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @todo Phpstan type.
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyColor($properties)
            ->setPropertyMerged($properties)
            ->setPropertyContains($properties)
            ->setPropertyPointsAt($properties)
            ->setPropertyRemotes($properties)
            ->setPropertyAll($properties)
            ->setPropertySort($properties)
            ->setPropertyIgnoreCase($properties)
            ->setPropertyFormat($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    protected function preGetCliCommand(): static
    {
        return parent::preGetCliCommand()
            ->preGetCliCommandFormat();
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new getBranchesParser();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatRefPropertyMapping(): ?array
    {
        return $this->utils->predefinedRefPropertyMappings['branch-list.default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        return [
            'definition' => $this->properties['commandOptions']['format']['definition'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMachineReadableFormatConfig(): array
    {
        return [
            'keyProperty' => 'refName',
            'refSeparatorPosition' => 'begin',
            'refPropertyMapping' => $this->getFormatRefPropertyMapping(),
        ];
    }
}
