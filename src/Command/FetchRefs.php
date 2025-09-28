<?php

declare(strict_types=1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionAppendTrait;
use Sweetchuck\Git\Option\OptionAtomicTrait;
use Sweetchuck\Git\Option\OptionAutoMaintenanceTrait;
use Sweetchuck\Git\Option\OptionDeepenTrait;
use Sweetchuck\Git\Option\OptionDepthTrait;
use Sweetchuck\Git\Option\OptionDryRunTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionIpvTrait;
use Sweetchuck\Git\Option\OptionJobsTrait;
use Sweetchuck\Git\Option\OptionKeepTrait;
use Sweetchuck\Git\Option\OptionNegotiationTipTrait;
use Sweetchuck\Git\Option\OptionPrefetchTrait;
use Sweetchuck\Git\Option\OptionPruneTagsTrait;
use Sweetchuck\Git\Option\OptionPruneTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionRefetchTrait;
use Sweetchuck\Git\Option\OptionServerOptionTrait;
use Sweetchuck\Git\Option\OptionSetUpstreamTrait;
use Sweetchuck\Git\Option\OptionShallowExcludeTrait;
use Sweetchuck\Git\Option\OptionShallowSinceTrait;
use Sweetchuck\Git\Option\OptionShowForcedUpdatesTrait;
use Sweetchuck\Git\Option\OptionSubmodulePrefixTrait;
use Sweetchuck\Git\Option\OptionTagsTrait;
use Sweetchuck\Git\Option\OptionUnshallowTrait;
use Sweetchuck\Git\Option\OptionUpdateHeadOkTrait;
use Sweetchuck\Git\Option\OptionUpdateShallowTrait;
use Sweetchuck\Git\Option\OptionUploadPackTrait;
use Sweetchuck\Git\Option\OptionWriteCommitGraphTrait;
use Sweetchuck\Git\Option\OptionWriteFetchHeadTrait;

/**
 * Represents the "git fetch" command.
 *
 * @todo The "git fetch --negotiate-only" could be implemented as a separate class.
 * @todo --multiple
 * @todo ----refmap=<refspec>
 * @todo --recurse-submodules-default=[yes|on-demand]
 * @todo Learn more about the "remotes.<group>" configuration.
 */
class FetchRefs extends CliCommandBase
{
    use OptionAllTrait;
    use OptionAppendTrait;
    use OptionAtomicTrait;
    use OptionDryRunTrait;
    use OptionWriteFetchHeadTrait;
    use OptionForceTrait;
    use OptionKeepTrait;
    use OptionAutoMaintenanceTrait;
    use OptionWriteCommitGraphTrait;
    use OptionPrefetchTrait;
    use OptionPruneTrait;
    use OptionPruneTagsTrait;
    use OptionTagsTrait;
    use OptionRefetchTrait;
    use OptionShowForcedUpdatesTrait;
    use OptionUpdateShallowTrait;
    use OptionUnshallowTrait;
    use OptionSetUpstreamTrait;
    use OptionUpdateHeadOkTrait;
    use OptionIpvTrait;
    use OptionDepthTrait;
    use OptionDeepenTrait;
    use OptionJobsTrait;
    use OptionShallowSinceTrait;
    use OptionShallowExcludeTrait;
    use OptionNegotiationTipTrait;
    use OptionRecurseSubmodulesTrait;
    use OptionSubmodulePrefixTrait;
    use OptionUploadPackTrait;
    use OptionServerOptionTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['fetch'];
        $this->properties['commandArguments'][0] = null;
        $this
            ->initPropertyAll()
            ->initPropertyAppend()
            ->initPropertyAtomic()
            ->initPropertyDryRun()
            ->initPropertyWriteFetchHead()
            ->initPropertyForce()
            ->initPropertyKeep()
            ->initPropertyAutoMaintenance()
            ->initPropertyWriteCommitGraph()
            ->initPropertyPrefetch()
            ->initPropertyPrune()
            ->initPropertyPruneTags()
            ->initPropertyTags()
            ->initPropertyRefetch()
            ->initPropertyShowForcedUpdates()
            ->initPropertyUpdateShallow()
            ->initPropertyUnshallow()
            ->initPropertySetUpstream()
            ->initPropertyUpdateHeadOk()
            ->initPropertyIpv()
            ->initPropertyDepth()
            ->initPropertyDeepen()
            ->initPropertyJobs()
            ->initPropertyShallowSince()
            ->initPropertyShallowExclude()
            ->initPropertyNegotiationTip()
            ->initPropertyRecurseSubmodules()
            ->initPropertySubmodulePrefix()
            ->initPropertyUploadPack()
            ->initPropertyServerOption();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyAll($properties)
            ->setPropertyAppend($properties)
            ->setPropertyAtomic($properties)
            ->setPropertyDryRun($properties)
            ->setPropertyWriteFetchHead($properties)
            ->setPropertyForce($properties)
            ->setPropertyKeep($properties)
            ->setPropertyAutoMaintenance($properties)
            ->setPropertyWriteCommitGraph($properties)
            ->setPropertyPrefetch($properties)
            ->setPropertyPrune($properties)
            ->setPropertyPruneTags($properties)
            ->setPropertyTags($properties)
            ->setPropertyRefetch($properties)
            ->setPropertyShowForcedUpdates($properties)
            ->setPropertyUpdateShallow($properties)
            ->setPropertyUnshallow($properties)
            ->setPropertySetUpstream($properties)
            ->setPropertyUpdateHeadOk($properties)
            ->setPropertyIpv($properties)
            ->setPropertyDepth($properties)
            ->setPropertyDeepen($properties)
            ->setPropertyJobs($properties)
            ->setPropertyShallowSince($properties)
            ->setPropertyShallowExclude($properties)
            ->setPropertyNegotiationTip($properties)
            ->setPropertyRecurseSubmodules($properties)
            ->setPropertySubmodulePrefix($properties)
            ->setPropertyUploadPack($properties)
            ->setPropertyServerOption($properties);

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
