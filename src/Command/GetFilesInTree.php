<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionFormatTrait;
use Sweetchuck\Git\Option\OptionRecursiveTrait;
use Sweetchuck\Git\Option\OptionShowTreeEntriesTrait;
use Sweetchuck\Git\Option\OptionTreeEntriesWithoutChildrenTrait;
use Sweetchuck\Git\OutcomeParser\FormatParser;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Utils;

/**
 * Represents the "git ls-tree" command.
 *
 * @link https://git-scm.com/docs/git-ls-tree
 */
class GetFilesInTree extends CliCommandBase
{

    use OptionTreeEntriesWithoutChildrenTrait;
    use OptionRecursiveTrait;
    use OptionShowTreeEntriesTrait;
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

        $this->properties['command'] = ['ls-tree'];
        $this->properties['commandArguments'][0] = null;

        $this->properties['commandOptions']['null'] = [
            'type' => 'state:bool',
            'name' => '-z',
            'state' => true,
        ];

        $this
            ->initPropertyTreeEntriesWithoutChildren()
            ->initPropertyRecursive()
            ->initPropertyShowTreeEntries()
            ->initPropertyFormat();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyTreeEntriesWithoutChildren($properties)
            ->setPropertyRecursive($properties)
            ->setPropertyShowTreeEntries($properties)
            ->setPropertyFormat($properties);

        if (array_key_exists('treeish', $properties)) {
            $this->setTreeish($properties['treeish']);
        }

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    public function getTreeish(): ?string
    {
        return $this->properties['commandArguments'][0];
    }

    public function setTreeish(string $treeish): static
    {
        $this->properties['commandArguments'][0] = $treeish;

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
        return $this->utils->predefinedRefPropertyMappings['ls-tree.default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        return [
            'definition' => $this->properties['commandOptions']['format']['definition'],
            'assetKey' => 'paths',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMachineReadableFormatConfig(): array
    {
        return [
            'keyProperty' => 'path',
            'refSeparatorPosition' => 'after',
            'refSeparator' => "\0",
            'refPropertyMapping' => $this->getFormatRefPropertyMapping(),
        ];
    }
}
