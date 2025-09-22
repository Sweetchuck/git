<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * @see https://git-scm.com/docs/git-commit#Documentation/git-commit.txt---cleanupmode
 */
enum Cleanup: string
{
    case Strip = 'strip';

    case Whitespace = 'whitespace';

    case Verbatim = 'verbatim';

    case Scissors = 'scissors';

    case Default = 'default';
}
