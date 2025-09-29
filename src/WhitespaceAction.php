<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * @see https://git-scm.com/docs/git-apply#Documentation/git-apply.txt---whitespaceaction
 */
enum WhitespaceAction: string
{
    /**
     * Turns off the trailing whitespace warning.
     */
    case NoWarn = 'nowarn';

    /**
     * Outputs warnings for a few such errors, but applies the patch as-is (default).
     */
    case Warn = 'warn';

    /**
     * Outputs warnings for a few such errors, and applies the patch after fixing them
     * (strip is a synony — the tool used to consider only trailing whitespace
     * characters as errors, and the fix involved stripping them, but modern Gits do more).
     */
    case Fix = 'fix';

    /**
     * Outputs warnings for a few such errors, and refuses to apply the patch.
     */
    case Error = 'error';

    /**
     * Similar to "error" but shows all errors.
     */
    case ErrorAll = 'error-all';
}
