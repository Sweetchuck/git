<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionCachedTrait;
use Sweetchuck\Git\Option\OptionDirectoryTrait;
use Sweetchuck\Git\Option\OptionDryRunTrait;
use Sweetchuck\Git\Option\OptionErrorUnmatchTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionIgnoreUnmatchTrait;
use Sweetchuck\Git\Option\OptionIgnoreMissingTrait;
use Sweetchuck\Git\Option\OptionNoEmptyDirectoryTrait;
use Sweetchuck\Git\Option\OptionPathSpecFromFileTrait;
use Sweetchuck\Git\Option\OptionRecursiveTrait;
use Sweetchuck\Git\Option\OptionSparseTrait;

/**
 * Represents the "git rm" command.
 *
 * @see https://git-scm.com/docs/git-rm
 */
class RemoveFiles extends CliCommandBase
{
    use OptionForceTrait;
    use OptionDryRunTrait;
    use OptionCachedTrait;
    use OptionRecursiveTrait;
    use OptionNoEmptyDirectoryTrait;
    use OptionIgnoreMissingTrait;
    use OptionIgnoreUnmatchTrait;
    use OptionSparseTrait;
    use OptionErrorUnmatchTrait;
    use OptionDirectoryTrait;
    use OptionPathSpecFromFileTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['rm'];

        $this
            ->initPropertyForce()
            ->initPropertyDryRun()
            ->initPropertyCached()
            ->initPropertyRecursive()
            ->initPropertyNoEmptyDirectory()
            ->initPropertyIgnoreMissing()
            ->initPropertyIgnoreUnmatch()
            ->initPropertySparse()
            ->initPropertyErrorUnmatch()
            ->initPropertyDirectory();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyForce($properties)
            ->setPropertyDryRun($properties)
            ->setPropertyCached($properties)
            ->setPropertyRecursive($properties)
            ->setPropertyNoEmptyDirectory($properties)
            ->setPropertyIgnoreMissing($properties)
            ->setPropertyIgnoreUnmatch($properties)
            ->setPropertySparse($properties)
            ->setPropertyErrorUnmatch($properties)
            ->setPropertyDirectory($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }
}
