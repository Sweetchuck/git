<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\FilePathStyle;
use Sweetchuck\Git\Option\OptionDiffFilterTrait;
use Sweetchuck\Git\OutcomeParser\GetChangedFilesParser;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Utils;

/**
 * Represents the "git diff --name-status --cached" command.
 */
class GetStagedFiles extends CliCommandBase
{
    use ArgumentPathsTrait;
    use OptionDiffFilterTrait;

    // region utils
    public function getUtils(): Utils
    {
        return $this->utils;
    }

    public function setUtils(Utils $utils): static
    {
        $this->utils = $utils;

        return $this;
    }
    // endregion

    // region filePathStyle
    protected FilePathStyle $filePathStyle = FilePathStyle::RelativeToTopLevel;

    public function getFilePathStyle(): FilePathStyle
    {
        return $this->filePathStyle;
    }

    public function setFilePathStyle(int|string|FilePathStyle $filePathStyle): static
    {
        if (is_int($filePathStyle)) {
            $filePathStyle = FilePathStyle::fromInteger($filePathStyle);
        } elseif (is_string($filePathStyle)) {
            $filePathStyle = FilePathStyle::from($filePathStyle);
        }

        $this->filePathStyle = $filePathStyle;

        return $this;
    }
    // endregion

    // region topLevel
    protected ?string $topLevel = null;

    public function getTopLevel(): ?string
    {
        return $this->topLevel;
    }

    public function setTopLevel(?string $topLevel): static
    {
        $this->topLevel = $topLevel;

        return $this;
    }
    // endregion

    public function __construct(
        protected ?Utils $utils = null,
    ) {
        if ($this->utils === null) {
            $this->utils = new Utils();
        }
        parent::__construct();
    }

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['diff'];

        $this->properties['globalOptions']['pager'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => false,
        ];

        $this->properties['commandOptions']['color'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => false,
        ];
        $this->properties['commandOptions']['nameStatus'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['cached'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['null'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'name' => '-z',
            'state' => true,
        ];

        $this->initPropertyDiffFilter();
        $this->properties['commandOptions']['relative'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyDiffFilter($properties);
        if (array_key_exists('filePathStyle', $properties)) {
            $this->setFilePathStyle($properties['filePathStyle']);
        }

        if (array_key_exists('topLevel', $properties)) {
            $this->setTopLevel($properties['topLevel']);
        }

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    protected function preGetCliCommand(): static
    {
        return parent::preGetCliCommand()
            ->preGetCliCommandRelative();
    }

    protected function preGetCliCommandRelative(): static
    {
        $this->properties['commandOptions']['relative']['state']
            = ($this->getFilePathStyle() === FilePathStyle::RelativeToWorkingDirectory) ?: null;

        return $this;
    }

    protected function getOutputParserOptions(): array
    {
        $options = parent::getOutputParserOptions();
        $options['filePathStyle'] = $this->getFilePathStyle();
        $options['topLevel'] = $this->getTopLevel();

        return $options;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetChangedFilesParser();
    }
}
