<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * Represents the status of a file in a Git repository.
 *
 * These statuses are returned by the following commands:
 * - `git status --porcelain`
 * - `git ls-files -t`
 * - `git log --name-status`
 *
 * @todo Separate Enum for "status" and "ls-files" and "log".
 */
enum FileStatus: string
{
    /**
     * Tracked file that is not either unmerged or skip-worktree.
     */
    case Tracked = 'H';

    /**
     * Tracked file that is skip-worktree.
     */
    case SkipWorktree = 'S';

    case Added = 'A';

    /**
     * Tracked file that is unmerged.
     */
    case Unmerged = 'M';

    case Deleted = 'D';

    /**
     * Tracked file with unstaged removal/deletion.
     */
    case UnstagedRemoval = 'R';

    /**
     * Tracked file with unstaged modification/change.
     */
    case UnstagedModification = 'C';

    /**
     * Untracked paths which are part of file/directory conflicts
     * which prevent checking out tracked files.
     */
    case UntrackedConflict = 'K';

    /**
     * Untracked file.
     */
    case Untracked = '?';

    /**
     * File with resolve-undo information.
     */
    case ResolveUndo = 'U';

    /**
     * "T" is never returned by any of the Git output.
     *
     * @see \Sweetchuck\Git\OutcomeParser\FormatParser::parseNameStatus
     */
    case Renamed = 'T';

    case Changed = 'X';

    /**
     * Checks if a status code represents a tracked file.
     *
     * @return bool
     *   True if the status code represents a tracked file, false otherwise.
     */
    public function isTracked(): bool
    {
        return in_array(
            $this,
            [
                self::Tracked,
                self::SkipWorktree,
                self::Unmerged,
                self::UnstagedRemoval,
                self::UnstagedModification,
            ],
            true,
        );
    }

    /**
     * Checks if a status code represents an untracked file.
     *
     * @return bool
     *   True if the status code represents an untracked file, false otherwise.
     */
    public function isUntracked(): bool
    {
        return in_array(
            $this,
            [
                self::Untracked,
                self::UntrackedConflict,
            ],
            true,
        );
    }

    public static function fromLogNameStatus(string $letter): self
    {
        return match ($letter) {
            'R' => self::Renamed,
            'M' => self::Changed,
            default => self::from($letter),
        };
    }
}
