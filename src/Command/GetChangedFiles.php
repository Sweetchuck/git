<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\CommandArgumentsTrait;
use Sweetchuck\Git\Option\OptionMergeBaseTrait;
use Sweetchuck\Git\Option\OptionNoIndexTrait;

/**
 * Represents the "git diff --name-only" command.
 *
 * @todo Implement the missing options.
 *
 * Missing options:
 * --find-renames[=<n>]
 * --find-copies[=<n>]
 * --find-copies-harder
 * --break-rewrites[=[<n>][/<m>]]
 *
 * Maybe not relevant options:
 * --ignore-space-change
 * --ignore-all-space
 * --ignore-blank-lines
 * --ignore-cr-at-eol
 * --ignore-space-at-eol
 */
class GetChangedFiles extends GetStagedFiles
{
    use OptionMergeBaseTrait;
    use OptionNoIndexTrait;
    use CommandArgumentsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        unset($this->properties['commandOptions']['cached']);

        $this
            ->initPropertyMergeBase()
            ->initPropertyNoIndex();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyMergeBase($properties)
            ->setPropertyNoIndex($properties);

        if (array_key_exists('commandArguments', $properties)) {
            $this->setCommandArguments($properties['commandArguments']);
        }

        return $this;
    }
}
