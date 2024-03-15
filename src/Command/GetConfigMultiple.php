<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\ConfigValueHandler;
use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionConfigScopeTrait;
use Sweetchuck\Git\Option\OptionFileTrait;
use Sweetchuck\Git\OutcomeParser\GetConfigMultipleParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git config list" command.
 */
class GetConfigMultiple extends CliCommandBase
{
    use OptionConfigScopeTrait;
    use OptionAllTrait;
    use OptionFileTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = [
            'config',
            'list',
        ];
        $this->properties['commandOptions']['null'] = [
            'type' => 'state:bool',
            'state' => true,
        ];
        $this->properties['commandOptions']['showScope'] = [
            'type' => 'state:bool',
            'name' => '--show-scope',
            'state' => true,
        ];
        $this->properties['commandOptions']['showOrigin'] = [
            'type' => 'state:bool',
            'name' => '--show-origin',
            'state' => true,
        ];
        $this->properties['commandOptions']['showNames'] = [
            'type' => 'state:bool',
            'name' => '--show-names',
            'state' => true,
        ];

        $this
            ->initPropertyConfigScope()
            ->initPropertyFile();

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
            ->setPropertyConfigScope($properties);

        if (array_key_exists('file', $properties)) {
            $this->setFile($properties['file']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetConfigMultipleParser(
            new ConfigValueHandler(),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        return [
            'all' => false,
        ];
    }
}
