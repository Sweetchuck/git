<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionAllMatchTrait;
use Sweetchuck\Git\Option\OptionCachedTrait;
use Sweetchuck\Git\Option\OptionContextTrait;
use Sweetchuck\Git\Option\OptionExcludeStandardTrait;
use Sweetchuck\Git\Option\OptionExpressionsTrait;
use Sweetchuck\Git\Option\OptionFileGrepTrait;
use Sweetchuck\Git\Option\OptionFilesWithMatchesTrait;
use Sweetchuck\Git\Option\OptionFullNameTrait;
use Sweetchuck\Git\Option\OptionIgnoreBinaryFilesTrait;
use Sweetchuck\Git\Option\OptionIgnoreCaseTrait;
use Sweetchuck\Git\Option\OptionInvertMatchTrait;
use Sweetchuck\Git\Option\OptionMaxCountTrait;
use Sweetchuck\Git\Option\OptionMaxDepthTrait;
use Sweetchuck\Git\Option\OptionNoIndexTrait;
use Sweetchuck\Git\Option\OptionOnlyMatchingTrait;
use Sweetchuck\Git\Option\OptionPatternTypeTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionShowFunctionTrait;
use Sweetchuck\Git\Option\OptionTextConvTrait;
use Sweetchuck\Git\Option\OptionTextTrait;
use Sweetchuck\Git\Option\OptionThreadsTrait;
use Sweetchuck\Git\Option\OptionUntrackedTrait;
use Sweetchuck\Git\Option\OptionWordRegexpTrait;
use Sweetchuck\Git\OutcomeParser\GrepFilesParser;
use Sweetchuck\Git\OutcomeParserInterface;

class GrepFiles extends CliCommandBase
{
    use OptionFilesWithMatchesTrait;
    use OptionCachedTrait;
    use OptionUntrackedTrait;
    use OptionNoIndexTrait;
    use OptionExcludeStandardTrait;
    use OptionRecurseSubmodulesTrait;
    use OptionTextTrait;
    use OptionTextConvTrait;
    use OptionIgnoreCaseTrait;
    use OptionIgnoreBinaryFilesTrait;
    use OptionMaxDepthTrait;
    use OptionInvertMatchTrait;
    use OptionWordRegexpTrait;
    use OptionFullNameTrait;
    use OptionPatternTypeTrait;
    use OptionOnlyMatchingTrait;
    use OptionShowFunctionTrait;
    use OptionContextTrait;
    use OptionMaxCountTrait;
    use OptionThreadsTrait;
    use OptionAllMatchTrait;
    use OptionFileGrepTrait;
    use OptionExpressionsTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['grep'];

        $this->properties['commandOptions']['color'] = [
            'type' => 'value:string-required',
            'value' => 'never',
        ];

        $this->properties['commandOptions']['null'] = [
            'type' => 'state:true',
            'state' => true,
        ];

        $this->properties['commandOptions']['line'] = [
            'type' => 'state:true',
            'state' => true,
        ];

        $this->properties['commandOptions']['column'] = [
            'type' => 'state:true',
            'state' => true,
        ];

        $this
            ->initPropertyFilesWithMatches()
            ->initPropertyCached()
            ->initPropertyUntracked()
            ->initPropertyNoIndex()
            ->initPropertyExcludeStandard()
            ->initPropertyRecurseSubmodules()
            ->initPropertyText()
            ->initPropertyTextConv()
            ->initPropertyIgnoreCase()
            ->initPropertyIgnoreBinaryFiles()
            ->initPropertyMaxDepth()
            ->initPropertyInvertMatch()
            ->initPropertyWordRegexp()
            ->initPropertyFullName()
            ->initPropertyPatternType()
            ->initPropertyOnlyMatching()
            ->initPropertyShowFunction()
            ->initPropertyContext()
            ->initPropertyMaxCount()
            ->initPropertyThreads()
            ->initPropertyAllMatch()
            ->initPropertyFile()
            ->initPropertyExpressions();

        $this->properties['commandArguments']['tree'] = null;

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyFilesWithMatches($properties)
            ->setPropertyCached($properties)
            ->setPropertyUntracked($properties)
            ->setPropertyNoIndex($properties)
            ->setPropertyExcludeStandard($properties)
            ->setPropertyRecurseSubmodules($properties)
            ->setPropertyText($properties)
            ->setPropertyTextConv($properties)
            ->setPropertyIgnoreCase($properties)
            ->setPropertyIgnoreBinaryFiles($properties)
            ->setPropertyMaxDepth($properties)
            ->setPropertyInvertMatch($properties)
            ->setPropertyWordRegexp($properties)
            ->setPropertyFullName($properties)
            ->setPropertyPatternType($properties)
            ->setPropertyOnlyMatching($properties)
            ->setPropertyShowFunction($properties)
            ->setPropertyContext($properties)
            ->setPropertyMaxCount($properties)
            ->setPropertyThreads($properties)
            ->setPropertyAllMatch($properties)
            ->setPropertyFile($properties)
            ->setPropertyExpressions($properties);

        if (array_key_exists('tree', $properties)) {
            $this->setTree($properties['tree']);
        }

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    public function getTree(): ?string
    {
        return $this->properties['commandArguments']['tree'];
    }

    public function setTree(?string $tree): static
    {
        $this->properties['commandArguments']['tree'] = $tree;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFinalProperties(): array
    {
        $properties = parent::getFinalProperties();
        $this->alterFinalPropertiesContext($properties);

        return $properties;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GrepFilesParser();
    }

    protected function getOutputParserOptions(): array
    {
        $options = parent::getOutputParserOptions();
        $options['stdOutputFormat'] = match ($this->getFilesWithMatches()) {
            true,
            false => 'onlyFilePaths',
            default => 'default',
        };

        return $options;
    }
}
