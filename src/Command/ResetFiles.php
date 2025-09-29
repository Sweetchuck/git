<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionGentlyTrait;
use Sweetchuck\Git\Option\OptionIntentToAddTrait;
use Sweetchuck\Git\Option\OptionKeepTrait;
use Sweetchuck\Git\Option\OptionMergeTrait;
use Sweetchuck\Git\Option\OptionPathSpecFromFileTrait;

/**
 * Represents the "git reset" command.
 *
 * @see https://git-scm.com/docs/git-reset
 */
class ResetFiles extends CliCommandBase
{
    use OptionGentlyTrait;
    use OptionMergeTrait;
    use OptionIntentToAddTrait;
    use OptionKeepTrait;
    use OptionPathSpecFromFileTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['reset'];
        $this->properties['commandArguments'][0] = null;
        $this
            ->initPropertyGently()
            ->initPropertyMerge()
            ->initPropertyIntentToAdd()
            ->initPropertyKeep()
            ->initPropertyPathSpecFromFile();
        $this->properties['commandOptions']['intentToAdd']['name'] = '-N';

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyGently($properties)
            ->setPropertyMerge($properties)
            ->setPropertyIntentToAdd($properties)
            ->setPropertyKeep($properties)
            ->setPropertyPathSpecFromFile($properties);

        if (array_key_exists('refName', $properties)) {
            $this->setRefName($properties['refName']);
        }

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }

    public function getRefName(): ?string
    {
        return $this->properties['commandArguments'][0];
    }

    public function setRefName(?string $name): static
    {
        $this->properties['commandArguments'][0] = $name;

        return $this;
    }
}
