<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionFetchTrait;
use Sweetchuck\Git\Option\OptionMirrorRemoteAddTrait;
use Sweetchuck\Git\Option\OptionTagsTrait;
use Sweetchuck\Git\Option\OptionMasterTrait;
use Sweetchuck\Git\Option\OptionTrackTrait;

/**
 * Represents the "git remote add" command.
 *
 * @see https://git-scm.com/docs/git-remote#Documentation/git-remote.txt-emaddem
 *
 * @phpstan-import-type SweetchuckGitCommandAddRemoteProperties from \Sweetchuck\Git\Phpstan
 */
class AddRemote extends CliCommandBase
{
    use OptionFetchTrait;
    use OptionMirrorRemoteAddTrait;
    use OptionTagsTrait;
    use OptionMasterTrait;
    use OptionTrackTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'add'];
        $this->properties['commandArguments'] = [
            'name' => null,
            'url' => null,
        ];

        $this
            ->initPropertyFetch()
            ->initPropertyMirror()
            ->initPropertyTags()
            ->initPropertyMaster()
            ->initPropertyTrack();

        return $this;
    }

    /**
     * @phpstan-param SweetchuckGitCommandAddRemoteProperties $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        $this
            ->setPropertyFetch($properties)
            ->setPropertyMirror($properties)
            ->setPropertyTags($properties)
            ->setPropertyMaster($properties)
            ->setPropertyTrack($properties);

        if (array_key_exists('name', $properties)) {
            $this->setName($properties['name']);
        }

        if (array_key_exists('url', $properties)) {
            $this->setUrl($properties['url']);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setName(?string $value): static
    {
        $this->properties['commandArguments']['name'] = $value;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->properties['commandArguments']['url'];
    }

    public function setUrl(?string $value): static
    {
        $this->properties['commandArguments']['url'] = $value;

        return $this;
    }
}
