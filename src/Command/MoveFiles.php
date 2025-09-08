<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionDryRunTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionSkipErrorsTrait;

/**
 * Represents the "git mv" command.
 *
 * @see https://git-scm.com/docs/git-mv
 *
 * @todo Artifacts when --dry-run is used.
 *
 * @phpstan-import-type SweetchuckGitCommandMoveFilesProperties from \Sweetchuck\Git\Phpstan
 */
class MoveFiles extends CliCommandBase
{
    use OptionForceTrait;
    use OptionDryRunTrait;
    use OptionSkipErrorsTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['mv'];
        $this
            ->initPropertyForce()
            ->initPropertyDryRun()
            ->initPropertySkipErrors();

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
            ->setPropertySkipErrors($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }
}
