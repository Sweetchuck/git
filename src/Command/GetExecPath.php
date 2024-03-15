<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\OutcomeParser\LastLineParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git --exec-path" command.
 */
class GetExecPath extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['globalOptions']['execPath'] = [
            'type' => 'state:bool',
            'name' => '--exec-path',
            'state' => true,
        ];

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new LastLineParser();
    }

    protected function getOutputParserOptions(): array
    {
        return [
            'key' => 'execPath',
        ];
    }
}
