<?php

declare(strict_types=1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAllowUnrelatedHistoriesTrait;
use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionAppendTrait;
use Sweetchuck\Git\Option\OptionAtomicTrait;
use Sweetchuck\Git\Option\OptionAutoStashTrait;
use Sweetchuck\Git\Option\OptionCleanupTrait;
use Sweetchuck\Git\Option\OptionCommitTrait;
use Sweetchuck\Git\Option\OptionDeepenTrait;
use Sweetchuck\Git\Option\OptionDepthTrait;
use Sweetchuck\Git\Option\OptionDryRunTrait;
use Sweetchuck\Git\Option\OptionFastForwardTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionGpgSignTrait;
use Sweetchuck\Git\Option\OptionIpvTrait;
use Sweetchuck\Git\Option\OptionJobsTrait;
use Sweetchuck\Git\Option\OptionKeepTrait;
use Sweetchuck\Git\Option\OptionLogTrait;
use Sweetchuck\Git\Option\OptionNegotiationTipTrait;
use Sweetchuck\Git\Option\OptionPrefetchTrait;
use Sweetchuck\Git\Option\OptionPruneTrait;
use Sweetchuck\Git\Option\OptionRebaseTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionServerOptionTrait;
use Sweetchuck\Git\Option\OptionSetUpstreamTrait;
use Sweetchuck\Git\Option\OptionShallowExcludeTrait;
use Sweetchuck\Git\Option\OptionShallowSinceTrait;
use Sweetchuck\Git\Option\OptionShowForcedUpdatesTrait;
use Sweetchuck\Git\Option\OptionSquashTrait;
use Sweetchuck\Git\Option\OptionStrategiesTrait;
use Sweetchuck\Git\Option\OptionTagsTrait;
use Sweetchuck\Git\Option\OptionUnshallowTrait;
use Sweetchuck\Git\Option\OptionUpdateShallowTrait;
use Sweetchuck\Git\Option\OptionUploadPackTrait;
use Sweetchuck\Git\Option\OptionVerifySignaturesTrait;
use Sweetchuck\Git\Option\OptionVerifyTrait;

/**
 * Represents the "git pull" command.
 *
 * @todo The "git fetch --negotiate-only" could be implemented as a separate class.
 */
class PullRefs extends CliCommandBase
{
    use OptionAllTrait;
    use OptionAppendTrait;
    use OptionAtomicTrait;
    use OptionCommitTrait;
    use OptionSquashTrait;
    use OptionVerifyTrait;
    use OptionVerifySignaturesTrait;
    use OptionAutoStashTrait;
    use OptionAllowUnrelatedHistoriesTrait;
    use OptionRebaseTrait;
    use OptionUnshallowTrait;
    use OptionUpdateShallowTrait;
    use OptionDryRunTrait;
    use OptionForceTrait;
    use OptionKeepTrait;
    use OptionPrefetchTrait;
    use OptionPruneTrait;
    use OptionTagsTrait;
    use OptionSetUpstreamTrait;
    use OptionShowForcedUpdatesTrait;
    use OptionGpgSignTrait;
    use OptionLogTrait;
    use OptionJobsTrait;
    use OptionDepthTrait;
    use OptionDeepenTrait;
    use OptionCleanupTrait;
    use OptionShallowSinceTrait;
    use OptionShallowExcludeTrait;
    use OptionNegotiationTipTrait;
    use OptionUploadPackTrait;
    use OptionServerOptionTrait;
    use OptionIpvTrait;
    use OptionFastForwardTrait;
    use OptionStrategiesTrait;
    use OptionRecurseSubmodulesTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['pull'];
        $this->properties['commandArguments'][0] = null;
        $this
            // Bool.
            ->initPropertyAll()
            ->initPropertyAppend()
            ->initPropertyAtomic()
            ->initPropertyCommit()
            ->initPropertySquash()
            ->initPropertyVerify()
            ->initPropertyVerifySignatures()
            ->initPropertyAutoStash()
            ->initPropertyAllowUnrelatedHistories()
            ->initPropertyRebase()
            ->initPropertyUnshallow()
            ->initPropertyUpdateShallow()
            ->initPropertyDryRun()
            ->initPropertyForce()
            ->initPropertyKeep()
            ->initPropertyPrefetch()
            ->initPropertyPrune()
            ->initPropertyTags()
            ->initPropertySetUpstream()
            ->initPropertyShowForcedUpdates()
            // Bool and string.
            ->initPropertyGpgSign()
            ->initPropertyLog()
            // String.
            ->initPropertyJobs()
            ->initPropertyDepth()
            ->initPropertyDeepen()
            ->initPropertyCleanup()
            ->initPropertyShallowSince()
            ->initPropertyShallowExclude()
            ->initPropertyNegotiationTip()
            ->initPropertyUploadPack()
            ->initPropertyServerOption()
            // Other.
            ->initPropertyIpv()
            ->initPropertyFastForward()
            ->initPropertyStrategies()
            ->initPropertyRecurseSubmodules();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyAll($properties)
            ->setPropertyAppend($properties)
            ->setPropertyAtomic($properties)
            ->setPropertyCleanup($properties)
            ->setPropertySquash($properties)
            ->setPropertyVerify($properties)
            ->setPropertyVerifySignatures($properties)
            ->setPropertyAutoStash($properties)
            ->setPropertyAllowUnrelatedHistories($properties)
            ->setPropertyRebase($properties)
            ->setPropertyUnshallow($properties)
            ->setPropertyUpdateShallow($properties)
            ->setPropertyDryRun($properties)
            ->setPropertyForce($properties)
            ->setPropertyKeep($properties)
            ->setPropertyPrefetch($properties)
            ->setPropertyPrune($properties)
            ->setPropertyTags($properties)
            ->setPropertySetUpstream($properties)
            ->setPropertyShowForcedUpdates($properties)
            ->setPropertyGpgSign($properties)
            ->setPropertyLog($properties)
            ->setPropertyJobs($properties)
            ->setPropertyDepth($properties)
            ->setPropertyDeepen($properties)
            ->setPropertyShallowSince($properties)
            ->setPropertyShallowExclude($properties)
            ->setPropertyNegotiationTip($properties)
            ->setPropertyUploadPack($properties)
            ->setPropertyServerOption($properties)
            ->setPropertyIpv($properties)
            ->setPropertyFastForward($properties)
            ->setPropertyStrategies($properties)
            ->setPropertyRecurseSubmodules($properties);

        if (array_key_exists('repository', $properties)) {
            $this->setRepository($properties['repository']);
        }

        if (array_key_exists('refs', $properties)) {
            $this->setRefs($properties['refs']);
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

    /**
     * @return array<string>
     */
    public function getRefs(): array
    {
        return array_slice($this->properties['commandArguments'], 1);
    }

    /**
     * @param array<string> $names
     */
    public function setRefs(array $names): static
    {
        $this->properties['commandArguments'] = [$this->getRepository(), ...$names];

        return $this;
    }
}
