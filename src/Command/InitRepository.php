<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionBareTrait;
use Sweetchuck\Git\Option\OptionInitialBranchTrait;
use Sweetchuck\Git\Option\OptionObjectFormatTrait;
use Sweetchuck\Git\Option\OptionRefFormatTrait;
use Sweetchuck\Git\Option\OptionSeparateGitDirTrait;
use Sweetchuck\Git\Option\OptionSharedTrait;
use Sweetchuck\Git\Option\OptionTemplateTrait;
use Sweetchuck\Git\OutcomeParser\InitRepositoryParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git init" command.
 */
class InitRepository extends CliCommandBase
{
    use OptionBareTrait;
    use OptionInitialBranchTrait;
    use OptionObjectFormatTrait;
    use OptionRefFormatTrait;
    use OptionSeparateGitDirTrait;
    use OptionSharedTrait;
    use OptionTemplateTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['init'];

        $this
            ->initPropertyBare()
            ->initPropertyTemplate()
            ->initPropertySeparateGitDir()
            ->initPropertyObjectFormat()
            ->initPropertyRefFormat()
            ->initPropertyInitialBranch()
            ->initPropertyShared();

        $this->properties['commandArguments'] = [
            'directory' => null,
        ];

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->properties['commandArguments']['directory'];
    }

    public function setDirectory(?string $directory): static
    {
        $this->properties['commandArguments']['directory'] = $directory;

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyBare($properties)
            ->setPropertyTemplate($properties)
            ->setPropertySeparateGitDir($properties)
            ->setPropertyObjectFormat($properties)
            ->setPropertyRefFormat($properties)
            ->setPropertyInitialBranch($properties)
            ->setPropertyShared($properties);

        if (array_key_exists('directory', $properties)) {
            $this->setDirectory($properties['directory']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new InitRepositoryParser();
    }

    protected function getOutputParserOptions(): array
    {
        $options = parent::getOutputParserOptions();
        $options['isBare'] = $this->getBare();

        return $options;
    }
}
