<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionFormatTrait;
use Sweetchuck\Git\Option\OptionPatternTypeTrait;
use Sweetchuck\Git\Option\OptionSortTrait;
use Sweetchuck\Git\Option\OptionFollowTrait;
use Sweetchuck\Git\Option\OptionAuthorTrait;
use Sweetchuck\Git\Option\OptionCommitterTrait;
use Sweetchuck\Git\Option\OptionMaxCountTrait;
use Sweetchuck\Git\Option\OptionSkipTrait;
use Sweetchuck\Git\Option\OptionSinceTrait;
use Sweetchuck\Git\Option\OptionUntilTrait;
use Sweetchuck\Git\OutcomeParser\FormatParser;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Utils;

/**
 * Represents the "git log" command.
 */
class GetCommits extends CliCommandBase
{
    use OptionAllTrait;
    use OptionSortTrait;
    use OptionFormatTrait;
    use OptionPatternTypeTrait;
    use OptionFollowTrait;
    use OptionAuthorTrait;
    use OptionCommitterTrait;
    use OptionMaxCountTrait;
    use OptionSkipTrait;
    use OptionSinceTrait;
    use OptionUntilTrait;
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

        $this->properties['command'] = ['log'];
        $this->properties['commandOptions']['null'] = [
            'type' => 'state:bool',
            'name' => '-z',
            'state' => true,
        ];
        $this->properties['commandOptions']['color'] = [
            'type' => 'state:bool',
            'state' => false,
        ];
        $this->properties['commandOptions']['date'] = [
            'type' => 'value:string-required',
            'value' => 'iso',
        ];
        $this->properties['commandOptions']['nameStatus'] = [
            'type' => 'state:bool',
            'state' => true,
        ];
        // @todo <revision-range>
        // @todo --grep
        // @todo --all-match
        // @todo --invert-grep
        // @todo --reverse
        // @todo --since-as-filter=<date>
        // @todo --merges --no-merges
        // @todo --min-parents=<number>
        // @todo --max-parents=<number>
        // @todo --no-min-parents
        // @todo --no-max-parents
        // @todo --ignore-missing
        $this
            ->initPropertyFormat()
            ->initPropertyAll()
            ->initPropertySort()
            ->initPropertyPatternType()
            ->initPropertyFollow()
            ->initPropertyAuthor()
            ->initPropertyCommitter()
            ->initPropertyMaxCount()
            ->initPropertySkip()
            ->initPropertySince()
            ->initPropertyUntil();

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
            ->setPropertyFormat($properties)
            ->setPropertyAll($properties)
            ->setPropertySort($properties)
            ->setPropertyPatternType($properties)
            ->setPropertyFollow($properties)
            ->setPropertyAuthor($properties)
            ->setPropertyCommitter($properties)
            ->setPropertyMaxCount($properties)
            ->setPropertySkip($properties)
            ->setPropertySince($properties)
            ->setPropertyUntil($properties);

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
        return $this->utils->predefinedRefPropertyMappings['log-list.default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        $definition = $this->properties['commandOptions']['format']['definition'];

        return [
            'assetKey' => 'commits',
            'definition' => $definition,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMachineReadableFormatConfig(): array
    {
        return [
            'keyProperty' => 'commitHash',
            'refSeparatorPosition' => 'begin',
            'refPropertyMapping' => $this->getFormatRefPropertyMapping(),
        ];
    }
}
