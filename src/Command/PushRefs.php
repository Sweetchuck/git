<?php

declare(strict_types=1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionAtomicTrait;
use Sweetchuck\Git\Option\OptionDeleteTrait;
use Sweetchuck\Git\Option\OptionDryRunTrait;
use Sweetchuck\Git\Option\OptionExecTrait;
use Sweetchuck\Git\Option\OptionFollowTagsTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionForceWithLeaseTrait;
use Sweetchuck\Git\Option\OptionIpvTrait;
use Sweetchuck\Git\Option\OptionMirrorCloneTrait;
use Sweetchuck\Git\Option\OptionPruneTrait;
use Sweetchuck\Git\Option\OptionPushOptionTrait;
use Sweetchuck\Git\Option\OptionReceivePackTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;
use Sweetchuck\Git\Option\OptionSetUpstreamTrait;
use Sweetchuck\Git\Option\OptionSignedTrait;
use Sweetchuck\Git\Option\OptionTagsTrait;
use Sweetchuck\Git\Option\OptionThinTrait;
use Sweetchuck\Git\Option\OptionVerifyTrait;

/**
 * Represents the "git push" command.
 *
 * @todo The "git push upstream --delete not-needed" could be implemented as a separate class.
 */
class PushRefs extends CliCommandBase
{
    use OptionAllTrait;
    use OptionPruneTrait;
    use OptionMirrorCloneTrait;
    use OptionDryRunTrait;
    use OptionDeleteTrait;
    use OptionTagsTrait;
    use OptionFollowTagsTrait;
    use OptionSignedTrait;
    use OptionAtomicTrait;
    use OptionPushOptionTrait;
    use OptionReceivePackTrait;
    use OptionExecTrait;
    use OptionForceWithLeaseTrait;
    use OptionForceTrait;
    use OptionSetUpstreamTrait;
    use OptionThinTrait;
    use OptionRecurseSubmodulesTrait;
    use OptionVerifyTrait;
    use OptionIpvTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['push'];
        $this->properties['commandArguments'][0] = null;
        $this
            ->initPropertyAll()
            ->initPropertyPrune()
            ->initPropertyMirror()
            ->initPropertyDryRun()
            ->initPropertyDelete()
            ->initPropertyTags()
            ->initPropertyFollowTags()
            ->initPropertySigned()
            ->initPropertyAtomic()
            ->initPropertyPushOption()
            ->initPropertyReceivePack()
            ->initPropertyExec()
            ->initPropertyForceWithLease()
            ->initPropertyForce()
            ->initPropertySetUpstream()
            ->initPropertyThin()
            ->initPropertyRecurseSubmodules()
            ->initPropertyVerify()
            ->initPropertyIpv();

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyAll($properties)
            ->setPropertyPrune($properties)
            ->setPropertyMirror($properties)
            ->setPropertyDryRun($properties)
            ->setPropertyDelete($properties)
            ->setPropertyTags($properties)
            ->setPropertyFollowTags($properties)
            ->setPropertySigned($properties)
            ->setPropertyAtomic($properties)
            ->setPropertyPushOption($properties)
            ->setPropertyReceivePack($properties)
            ->setPropertyExec($properties)
            ->setPropertyForceWithLease($properties)
            ->setPropertyForce($properties)
            ->setPropertySetUpstream($properties)
            ->setPropertyThin($properties)
            ->setPropertyRecurseSubmodules($properties)
            ->setPropertyVerify($properties)
            ->setPropertyIpv($properties);

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
