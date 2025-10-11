<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\OutcomeParser\GetVersionParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git --version" command.
 */
class GetVersion extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['globalOptions']['version'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetVersionParser();
    }
}
