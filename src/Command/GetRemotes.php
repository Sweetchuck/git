<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\OutcomeParser\GetRemotesParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git remote --verbose" command.
 *
 * @phpstan-import-type SweetchuckGitIgnoredModes from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitUntrackedFilesModes from \Sweetchuck\Git\Phpstan
 */
class GetRemotes extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote'];
        $this->properties['commandOptions']['verbose'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetRemotesParser();
    }
}
