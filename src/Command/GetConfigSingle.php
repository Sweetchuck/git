<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\ConfigValueHandler;
use Sweetchuck\Git\Option\OptionAllTrait;
use Sweetchuck\Git\Option\OptionConfigScopeTrait;
use Sweetchuck\Git\Option\OptionDefaultTrait;
use Sweetchuck\Git\Option\OptionRegexpTrait;
use Sweetchuck\Git\OutcomeParser\GetConfigSingleParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git config get" command.
 */
class GetConfigSingle extends CliCommandBase
{
    use OptionDefaultTrait;
    use OptionConfigScopeTrait;
    use OptionRegexpTrait;
    use OptionAllTrait;

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = [
            'config',
            'get',
        ];
        $this->properties['commandOptions']['null'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];
        $this->properties['commandOptions']['showScope'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'name' => '--show-scope',
            'state' => true,
        ];
        $this->properties['commandOptions']['showOrigin'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'name' => '--show-origin',
            'state' => true,
        ];
        $this->properties['commandOptions']['showNames'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'name' => '--show-names',
            'state' => true,
        ];

        $this
            ->initPropertyConfigScope()
            ->initPropertyAll()
            ->initPropertyRegexp()
            ->initPropertyDefault();

        $this->properties['commandArguments'] = [
            'init.defaultBranch',
        ];

        return $this;
    }

    public function getConfigName(): string
    {
        return $this->properties['commandArguments'][0];
    }

    public function setConfigName(string $name): static
    {
        $this->properties['commandArguments'][0] = $name;

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
            ->setPropertyRegexp($properties)
            ->setPropertyConfigScope($properties)
            ->setPropertyDefault($properties);

        if (array_key_exists('configName', $properties)) {
            $this->setConfigName($properties['configName']);
        }

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetConfigSingleParser(
            new ConfigValueHandler(),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        return [
            'name' => $this->getConfigName(),
            'all' => $this->getAll(),
            'regexp' => $this->getRegexp(),
        ];
    }
}
