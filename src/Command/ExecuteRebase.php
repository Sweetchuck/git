<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAllowEmptyMessageTrait;
use Sweetchuck\Git\Option\OptionApplyTrait;
use Sweetchuck\Git\Option\OptionAutoSquashTrait;
use Sweetchuck\Git\Option\OptionAutoStashTrait;
use Sweetchuck\Git\Option\OptionCommitterDateIsAuthorDateTrait;
use Sweetchuck\Git\Option\OptionContextRebaseTrait;
use Sweetchuck\Git\Option\OptionEmptyTrait;
use Sweetchuck\Git\Option\OptionExecRebaseTrait;
use Sweetchuck\Git\Option\OptionForkPointTrait;
use Sweetchuck\Git\Option\OptionGpgSignTrait;
use Sweetchuck\Git\Option\OptionIgnoreWhitespaceTrait;
use Sweetchuck\Git\Option\OptionKeepBaseTrait;
use Sweetchuck\Git\Option\OptionKeepEmptyTrait;
use Sweetchuck\Git\Option\OptionMergeTrait;
use Sweetchuck\Git\Option\OptionNoFastForwardTrait;
use Sweetchuck\Git\Option\OptionOnToTrait;
use Sweetchuck\Git\Option\OptionReapplyCherryPicksTrait;
use Sweetchuck\Git\Option\OptionRebaseMergesTrait;
use Sweetchuck\Git\Option\OptionRerereAutoupdateTrait;
use Sweetchuck\Git\Option\OptionResetAuthorDateTrait;
use Sweetchuck\Git\Option\OptionRootTrait;
use Sweetchuck\Git\Option\OptionSignoffTrait;
use Sweetchuck\Git\Option\OptionStrategiesTrait;
use Sweetchuck\Git\Option\OptionUpdateRefsTrait;
use Sweetchuck\Git\Option\OptionVerifyTrait;
use Sweetchuck\Git\Option\OptionWhitespaceTrait;

/**
 * Represents the "git rebase" command.
 */
class ExecuteRebase extends CliCommandBase
{
    use OptionOnToTrait;
    use OptionKeepBaseTrait;
    use OptionApplyTrait;
    use OptionEmptyTrait;
    use OptionKeepEmptyTrait;
    use OptionReapplyCherryPicksTrait;
    use OptionAllowEmptyMessageTrait;
    use OptionMergeTrait;
    use OptionRerereAutoupdateTrait;
    use OptionVerifyTrait;
    use OptionContextRebaseTrait;
    use OptionNoFastForwardTrait;
    use OptionForkPointTrait;
    use OptionIgnoreWhitespaceTrait;
    use OptionCommitterDateIsAuthorDateTrait;
    use OptionResetAuthorDateTrait;
    use OptionSignoffTrait;
    use OptionRootTrait;
    use OptionAutoSquashTrait;
    use OptionAutoStashTrait;
    use OptionUpdateRefsTrait;
    use OptionRebaseMergesTrait;
    use OptionWhitespaceTrait;
    use OptionStrategiesTrait;
    use OptionGpgSignTrait;
    use OptionExecRebaseTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['rebase'];
        $this->properties['commandArguments'][0] = null;
        $this->properties['commandArguments'][1] = null;

        $this
            // Bool.
            ->initPropertyKeepBase()
            ->initPropertyApply()
            ->initPropertyEmpty()
            ->initPropertyKeepEmpty()
            ->initPropertyReapplyCherryPicks()
            ->initPropertyAllowEmptyMessage()
            ->initPropertyMerge()
            ->initPropertyRerereAutoupdate()
            ->initPropertyVerify()
            ->initPropertyNoFastForward()
            ->initPropertyForkPoint()
            ->initPropertyIgnoreWhitespace()
            ->initPropertyCommitterDateIsAuthorDate()
            ->initPropertyResetAuthorDate()
            ->initPropertySignoff()
            ->initPropertyRoot()
            ->initPropertyAutoSquash()
            ->initPropertyAutoStash()
            ->initPropertyUpdateRefs()
            // Bool and string.
            ->initPropertyRebaseMerges()
            // String.
            ->initPropertyOnTo()
            ->initPropertyContext()
            ->initPropertyWhitespace()
            // Other.
            ->initPropertyStrategies()
            ->initPropertyGpgSign()
            ->initPropertyExec();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyKeepBase($properties)
            ->setPropertyApply($properties)
            ->setPropertyEmpty($properties)
            ->setPropertyKeepEmpty($properties)
            ->setPropertyReapplyCherryPicks($properties)
            ->setPropertyAllowEmptyMessage($properties)
            ->setPropertyMerge($properties)
            ->setPropertyRerereAutoupdate($properties)
            ->setPropertyVerify($properties)
            ->setPropertyContext($properties)
            ->setPropertyNoFastForward($properties)
            ->setPropertyForkPoint($properties)
            ->setPropertyIgnoreWhitespace($properties)
            ->setPropertyCommitterDateIsAuthorDate($properties)
            ->setPropertyResetAuthorDate($properties)
            ->setPropertySignoff($properties)
            ->setPropertyRoot($properties)
            ->setPropertyAutoSquash($properties)
            ->setPropertyAutoStash($properties)
            ->setPropertyUpdateRefs($properties)
            ->setPropertyRebaseMerges($properties)
            ->setPropertyOnTo($properties)
            ->setPropertyWhitespace($properties)
            ->setPropertyStrategies($properties)
            ->setPropertyGpgSign($properties)
            ->setPropertyExec($properties);

        if (array_key_exists('repository', $properties)) {
            $this->setRepository($properties['repository']);
        }

        if (array_key_exists('branch', $properties)) {
            $this->setBranch($properties['branch']);
        }

        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->properties['commandArguments'][0];
    }

    public function setRepository(string $name): static
    {
        $this->properties['commandArguments'][0] = $name;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->properties['commandArguments'][1];
    }

    public function setBranch(?string $name): static
    {
        $this->properties['commandArguments'][1] = $name;

        return $this;
    }
}
