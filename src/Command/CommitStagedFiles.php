<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionAllowEmptyTrait;
use Sweetchuck\Git\Option\OptionAmendTrait;
use Sweetchuck\Git\Option\OptionAuthorTrait;
use Sweetchuck\Git\Option\OptionDateTrait;
use Sweetchuck\Git\Option\OptionEditTrait;
use Sweetchuck\Git\Option\OptionFileTrait;
use Sweetchuck\Git\Option\OptionMessageTrait;
use Sweetchuck\Git\Option\OptionNoVerifyTrait;
use Sweetchuck\Git\Option\OptionSignoffTrait;
use Sweetchuck\Git\Option\OptionTemplateTrait;

/**
 * Represents the "git commit" command.
 */
class CommitStagedFiles extends CliCommandBase
{
    use OptionMessageTrait;
    use OptionAuthorTrait;
    use OptionDateTrait;
    use OptionAmendTrait;
    use OptionSignoffTrait;
    use OptionNoVerifyTrait;
    use OptionAllowEmptyTrait;
    use OptionEditTrait;
    use OptionFileTrait;
    use OptionTemplateTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['commit'];
        $this
            ->initPropertyMessage()
            ->initPropertyAuthor()
            ->initPropertyDate()
            ->initPropertyAmend()
            ->initPropertySignoff()
            ->initPropertyNoVerify()
            ->initPropertyAllowEmpty()
            ->initPropertyEdit()
            ->initPropertyFile()
            ->initPropertyTemplate();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyMessage($properties)
            ->setPropertyAuthor($properties)
            ->setPropertyDate($properties)
            ->setPropertyAmend($properties)
            ->setPropertySignoff($properties)
            ->setPropertyNoVerify($properties)
            ->setPropertyAllowEmpty($properties)
            ->setPropertyEdit($properties)
            ->setPropertyFile($properties)
            ->setPropertyTemplate($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }
}
