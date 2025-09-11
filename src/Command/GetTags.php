<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionColorTrait;
use Sweetchuck\Git\Option\OptionContainsTrait;
use Sweetchuck\Git\Option\OptionFormatTrait;
use Sweetchuck\Git\Option\OptionIgnoreCaseTrait;
use Sweetchuck\Git\Option\OptionMergedTrait;
use Sweetchuck\Git\Option\OptionPointsAtTrait;
use Sweetchuck\Git\Option\OptionSortTrait;
use Sweetchuck\Git\OutcomeParser\FormatParser;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Utils;

/**
 * Represents the "git tag --list" command.
 *
 * @phpstan-import-type SweetchuckGitIgnoredModes from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitUntrackedFilesModes from \Sweetchuck\Git\Phpstan
 */
class GetTags extends CliCommandBase
{
    use OptionColorTrait;
    use OptionContainsTrait;
    use OptionMergedTrait;
    use OptionPointsAtTrait;
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

        $this->properties['command'] = ['tag'];
        $this->properties['commandOptions']['list'] = [
            'type' => 'state:bool',
            'state' => true,
        ];
        $this
            ->initPropertyColor()
            ->initPropertyMerged()
            ->initPropertyContains()
            ->initPropertyPointsAt()
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
        return new FormatParser();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatRefPropertyMapping(): ?array
    {
        return $this->utils->predefinedRefPropertyMappings['tag-list.default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        return [
            'definition' => $this->properties['commandOptions']['format']['definition'],
            'assetKey' => 'tags',
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
