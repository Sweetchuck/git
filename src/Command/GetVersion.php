<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\OutcomeParser\GetVersionParser;
use Sweetchuck\Git\OutcomeParserInterface;

class GetVersion extends CliCommandBase
{

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetVersionParser();
    }

    public function getCliCommand(): array
    {
        $command = parent::getCliCommand();
        $command[] = '--version';

        return $command;
    }
}
