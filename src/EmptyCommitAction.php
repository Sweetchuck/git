<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * @see https://git-scm.com/docs/git-apply#Documentation/git-apply.txt---whitespaceaction
 */
enum EmptyCommitAction: string
{
    /**
     * The commit will be dropped. This is the default behavior.
     */
    case Drop = 'drop';

    /**
     * The commit will be kept.
     *
     * This option is implied when --exec is specified
     * unless -i/--interactive is also specified.
     */
    case Keep = 'keep';

    /**
     * The rebase will halt when the commit is applied,
     * allowing you to choose whether to drop it,
     * edit files more, or just commit the empty changes.
     * This option is implied when -i/--interactive is specified.
     */
    case Stop = 'stop';
}
