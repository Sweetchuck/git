<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionCachedTrait;
use Sweetchuck\Git\Option\OptionDryRunTrait;
use Sweetchuck\Git\Option\OptionForceTrait;
use Sweetchuck\Git\Option\OptionSparseTrait;
use Sweetchuck\Git\Option\OptionPatchTrait;
use Sweetchuck\Git\Option\OptionEditTrait;
use Sweetchuck\Git\Option\OptionUpdateTrait;
use Sweetchuck\Git\Option\OptionIntentToAddTrait;
use Sweetchuck\Git\Option\OptionIgnoreRemovalTrait;
use Sweetchuck\Git\Option\OptionPathSpecFromFileTrait;
use Sweetchuck\Git\Option\OptionPathSpecFileNulTrait;
use Sweetchuck\Git\Option\OptionChmodTrait;
use Sweetchuck\Git\Option\OptionRenormalizeTrait;
use Sweetchuck\Git\Option\OptionRefreshTrait;
use Sweetchuck\Git\Option\OptionIgnoreErrorsTrait;
use Sweetchuck\Git\Option\OptionIgnoreMissingTrait;
use Sweetchuck\Git\Option\OptionNoWarnEmbeddedRepoTrait;

/**
 * Represents the "git add" command.
 */
class StageFiles extends CliCommandBase
{
    use OptionAllTrait;
    use OptionDryRunTrait;
    use OptionForceTrait;
    use OptionCachedTrait;
    use OptionSparseTrait;
    use OptionPatchTrait;
    use OptionEditTrait;
    use OptionUpdateTrait;
    use OptionIntentToAddTrait;
    use OptionIgnoreRemovalTrait;
    use OptionPathSpecFromFileTrait;
    use OptionPathSpecFileNulTrait;
    use OptionChmodTrait;
    use OptionRenormalizeTrait;
    use OptionRefreshTrait;
    use OptionIgnoreErrorsTrait;
    use OptionIgnoreMissingTrait;
    use OptionNoWarnEmbeddedRepoTrait;
    use ArgumentPathsTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['add'];
        $this
            ->initPropertyAll()
            ->initPropertyDryRun()
            ->initPropertyForce()
            ->initPropertyCached()
            ->initPropertySparse()
            ->initPropertyPatch()
            ->initPropertyEdit()
            ->initPropertyUpdate()
            ->initPropertyIntentToAdd()
            ->initPropertyIgnoreRemoval()
            ->initPropertyPathSpecFromFile()
            ->initPropertyPathSpecFileNul()
            ->initPropertyChmod()
            ->initPropertyRenormalize()
            ->initPropertyRefresh()
            ->initPropertyIgnoreErrors()
            ->initPropertyIgnoreMissing()
            ->initPropertyNoWarnEmbeddedRepo();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this
            ->setPropertyAll($properties)
            ->setPropertyDryRun($properties)
            ->setPropertyForce($properties)
            ->setPropertyCached($properties)
            ->setPropertySparse($properties)
            ->setPropertyPatch($properties)
            ->setPropertyEdit($properties)
            ->setPropertyUpdate($properties)
            ->setPropertyIntentToAdd($properties)
            ->setPropertyIgnoreRemoval($properties)
            ->setPropertyPathSpecFromFile($properties)
            ->setPropertyPathSpecFileNul($properties)
            ->setPropertyChmod($properties)
            ->setPropertyRenormalize($properties)
            ->setPropertyRefresh($properties)
            ->setPropertyIgnoreErrors($properties)
            ->setPropertyIgnoreMissing($properties)
            ->setPropertyNoWarnEmbeddedRepo($properties);

        if (array_key_exists('paths', $properties)) {
            $this->setPaths($properties['paths']);
        }

        return $this;
    }
}
