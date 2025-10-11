<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAlsoFilterSubmodulesTrait;
use Sweetchuck\Git\Option\OptionBareTrait;
use Sweetchuck\Git\Option\OptionBranchTrait;
use Sweetchuck\Git\Option\OptionBundleUriTrait;
use Sweetchuck\Git\Option\OptionCheckoutTrait;
use Sweetchuck\Git\Option\OptionDepthTrait;
use Sweetchuck\Git\Option\OptionDissociateTrait;
use Sweetchuck\Git\Option\OptionFilterTrait;
use Sweetchuck\Git\Option\OptionHardlinksTrait;
use Sweetchuck\Git\Option\OptionIpvTrait;
use Sweetchuck\Git\Option\OptionJobsTrait;
use Sweetchuck\Git\Option\OptionLocalTrait;
use Sweetchuck\Git\Option\OptionMirrorCloneTrait;
use Sweetchuck\Git\Option\OptionOriginTrait;
use Sweetchuck\Git\Option\OptionReferenceIfAbleTrait;
use Sweetchuck\Git\Option\OptionReferenceTrait;
use Sweetchuck\Git\Option\OptionRefFormatTrait;
use Sweetchuck\Git\Option\OptionRejectShallowTrait;
use Sweetchuck\Git\Option\OptionRemoteSubmodulesTrait;
use Sweetchuck\Git\Option\OptionRevisionTrait;
use Sweetchuck\Git\Option\OptionSeparateGitDirTrait;
use Sweetchuck\Git\Option\OptionServerOptionTrait;
use Sweetchuck\Git\Option\OptionShallowExcludeTrait;
use Sweetchuck\Git\Option\OptionShallowSinceTrait;
use Sweetchuck\Git\Option\OptionShallowSubmodulesTrait;
use Sweetchuck\Git\Option\OptionSharedTrait;
use Sweetchuck\Git\Option\OptionSingleBranchTrait;
use Sweetchuck\Git\Option\OptionSparseTrait;
use Sweetchuck\Git\Option\OptionTagsTrait;
use Sweetchuck\Git\Option\OptionTemplateTrait;
use Sweetchuck\Git\Option\OptionUploadPackTrait;
use Sweetchuck\Git\Option\OptionRecurseSubmodulesTrait;

/**
 * Represents the "git clone" command.
 */
class CloneRepository extends CliCommandBase
{
    use OptionRejectShallowTrait;
    use OptionCheckoutTrait;
    use OptionBareTrait;
    use OptionMirrorCloneTrait;
    use OptionLocalTrait;
    use OptionHardlinksTrait;
    use OptionSharedTrait;
    use OptionRecurseSubmodulesTrait;
    use OptionJobsTrait;
    use OptionTemplateTrait;
    use OptionReferenceTrait;
    use OptionReferenceIfAbleTrait;
    use OptionDissociateTrait;
    use OptionOriginTrait;
    use OptionBranchTrait;
    use OptionRevisionTrait;
    use OptionUploadPackTrait;
    use OptionDepthTrait;
    use OptionShallowSinceTrait;
    use OptionShallowExcludeTrait;
    use OptionSingleBranchTrait;
    use OptionTagsTrait;
    use OptionShallowSubmodulesTrait;
    use OptionSeparateGitDirTrait;
    use OptionRefFormatTrait;
    use OptionServerOptionTrait;
    use OptionIpvTrait;
    use OptionFilterTrait;
    use OptionAlsoFilterSubmodulesTrait;
    use OptionRemoteSubmodulesTrait;
    use OptionSparseTrait;
    use OptionBundleUriTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['clone'];

        $this->properties['commandOptions']['progress'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => false,
        ];
        $this->properties['commandOptions']['config'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::ValueMap,
            'value' => [],
        ];
        $this
            ->initPropertyRejectShallow()
            ->initPropertyCheckout()
            ->initPropertyBare()
            ->initPropertyMirror()
            ->initPropertyLocal()
            ->initPropertyHardlinks()
            ->initPropertyShared()
            ->initPropertyRecurseSubmodules()
            ->initPropertyJobs()
            ->initPropertyTemplate()
            ->initPropertyReference()
            ->initPropertyReferenceIfAble()
            ->initPropertyDissociate()
            ->initPropertyOrigin()
            ->initPropertyBranch()
            ->initPropertyRevision()
            ->initPropertyUploadPack()
            ->initPropertyDepth()
            ->initPropertyShallowSince()
            ->initPropertyShallowExclude()
            ->initPropertySingleBranch()
            ->initPropertyTags()
            ->initPropertyShallowSubmodules()
            ->initPropertySeparateGitDir()
            ->initPropertyRefFormat()
            ->initPropertyServerOption()
            ->initPropertyIpv()
            ->initPropertyFilter()
            ->initPropertyAlsoFilterSubmodules()
            ->initPropertyRemoteSubmodules()
            ->initPropertySparse()
            ->initPropertyBundleUri();

        $this->properties['commandArguments'] = [
            'repository' => null,
            'directory' => null,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('config', $properties)) {
            $this->setConfig($properties['config']);
        }

        $this
            ->setPropertyRejectShallow($properties)
            ->setPropertyCheckout($properties)
            ->setPropertyBare($properties)
            ->setPropertyMirror($properties)
            ->setPropertyLocal($properties)
            ->setPropertyHardlinks($properties)
            ->setPropertyShared($properties)
            ->setPropertyRecurseSubmodules($properties)
            ->setPropertyJobs($properties)
            ->setPropertyTemplate($properties)
            ->setPropertyReference($properties)
            ->setPropertyReferenceIfAble($properties)
            ->setPropertyDissociate($properties)
            ->setPropertyOrigin($properties)
            ->setPropertyBranch($properties)
            ->setPropertyRevision($properties)
            ->setPropertyUploadPack($properties)
            ->setPropertyDepth($properties)
            ->setPropertyShallowSince($properties)
            ->setPropertyShallowExclude($properties)
            ->setPropertySingleBranch($properties)
            ->setPropertyTags($properties)
            ->setPropertyShallowSubmodules($properties)
            ->setPropertySeparateGitDir($properties)
            ->setPropertyRefFormat($properties)
            ->setPropertyServerOption($properties)
            ->setPropertyIpv($properties)
            ->setPropertyFilter($properties)
            ->setPropertyAlsoFilterSubmodules($properties)
            ->setPropertyRemoteSubmodules($properties)
            ->setPropertySparse($properties)
            ->setPropertyBundleUri($properties);

        if (array_key_exists('repository', $properties)) {
            $this->setRepository($properties['repository']);
        }

        if (array_key_exists('directory', $properties)) {
            $this->setDirectory($properties['directory']);
        }

        return $this;
    }

    /**
     * @param array<string, string> $config
     */
    public function setConfig(array $config): static
    {
        $this->properties['commandOptions']['config']['value'] = $config;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getConfig(): array
    {
        return $this->properties['commandOptions']['config']['value'];
    }

    public function getRepository(): ?string
    {
        return $this->properties['commandArguments']['repository'];
    }

    public function setRepository(string $repository): static
    {
        $this->properties['commandArguments']['repository'] = $repository;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->properties['commandArguments']['directory'];
    }

    public function setDirectory(?string $directory): static
    {
        $this->properties['commandArguments']['directory'] = $directory;

        return $this;
    }
}
