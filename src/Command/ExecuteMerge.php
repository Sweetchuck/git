<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\CommandArgumentsTrait;
use Sweetchuck\Git\Option\OptionAutoStashTrait;
use Sweetchuck\Git\Option\OptionCleanupTrait;
use Sweetchuck\Git\Option\OptionCommitTrait;
use Sweetchuck\Git\Option\OptionEditTrait;
use Sweetchuck\Git\Option\OptionFastForwardTrait;
use Sweetchuck\Git\Option\OptionFileTrait;
use Sweetchuck\Git\Option\OptionGpgSignTrait;
use Sweetchuck\Git\Option\OptionIntoNameTrait;
use Sweetchuck\Git\Option\OptionLogTrait;
use Sweetchuck\Git\Option\OptionMessageTrait;
use Sweetchuck\Git\Option\OptionOverwriteIgnoreTrait;
use Sweetchuck\Git\Option\OptionRerereAutoupdateTrait;
use Sweetchuck\Git\Option\OptionSignoffTrait;
use Sweetchuck\Git\Option\OptionSquashTrait;
use Sweetchuck\Git\Option\OptionStrategiesTrait;
use Sweetchuck\Git\Option\OptionVerifySignaturesTrait;
use Sweetchuck\Git\Option\OptionVerifyTrait;

class ExecuteMerge extends CliCommandBase
{
    use OptionSignoffTrait;
    use OptionSquashTrait;
    use OptionVerifyTrait;
    use OptionVerifySignaturesTrait;
    use OptionRerereAutoupdateTrait;
    use OptionOverwriteIgnoreTrait;
    use OptionCommitTrait;
    use OptionAutoStashTrait;
    use OptionEditTrait;
    use OptionFileTrait;
    use OptionMessageTrait;
    use OptionIntoNameTrait;
    use OptionCleanupTrait;
    use OptionGpgSignTrait;
    use OptionLogTrait;
    use OptionFastForwardTrait;
    use OptionStrategiesTrait;
    use CommandArgumentsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['merge'];

        $this
            ->initPropertySignoff()
            ->initPropertySquash()
            ->initPropertyVerify()
            ->initPropertyVerifySignatures()
            ->initPropertyRerereAutoupdate()
            ->initPropertyOverwriteIgnore()
            ->initPropertyCommit()
            ->initPropertyAutoStash()
            ->initPropertyEdit()
            ->initPropertyFile()
            ->initPropertyMessage()
            ->initPropertyIntoName()
            ->initPropertyCleanup()
            ->initPropertyGpgSign()
            ->initPropertyLog()
            ->initPropertyFastForward()
            ->initPropertyStrategies();
        $this->properties['commandOptions']['message']['name'] = '-m';

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertySignoff($properties)
            ->setPropertySquash($properties)
            ->setPropertyVerify($properties)
            ->setPropertyVerifySignatures($properties)
            ->setPropertyRerereAutoupdate($properties)
            ->setPropertyOverwriteIgnore($properties)
            ->setPropertyCommit($properties)
            ->setPropertyAutoStash($properties)
            ->setPropertyEdit($properties)
            ->setPropertyFile($properties)
            ->setPropertyMessage($properties)
            ->setPropertyIntoName($properties)
            ->setPropertyCleanup($properties)
            ->setPropertyGpgSign($properties)
            ->setPropertyLog($properties)
            ->setPropertyFastForward($properties)
            ->setPropertyStrategies($properties);

        if (array_key_exists('names', $properties)) {
            $this->setCommandArguments($properties['names']);
        }

        return $this;
    }
}
