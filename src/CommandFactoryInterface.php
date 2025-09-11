<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Sweetchuck\Git\Command\AddRemote;
use Sweetchuck\Git\Command\CloneRepository;
use Sweetchuck\Git\Command\CreateTag;
use Sweetchuck\Git\Command\DeleteTag;
use Sweetchuck\Git\Command\GetBranches;
use Sweetchuck\Git\Command\GetChangedFiles;
use Sweetchuck\Git\Command\GetCommits;
use Sweetchuck\Git\Command\GetConfigMultiple;
use Sweetchuck\Git\Command\GetConfigSingle;
use Sweetchuck\Git\Command\GetFileContent;
use Sweetchuck\Git\Command\GetFiles;
use Sweetchuck\Git\Command\GetRemotes;
use Sweetchuck\Git\Command\GetStagedFiles;
use Sweetchuck\Git\Command\GetStatus;
use Sweetchuck\Git\Command\GetTags;
use Sweetchuck\Git\Command\GrepFiles;
use Sweetchuck\Git\Command\InitRepository;
use Sweetchuck\Git\Command\MoveFiles;
use Sweetchuck\Git\Command\PruneRemote;
use Sweetchuck\Git\Command\RemoveRemote;
use Sweetchuck\Git\Command\RenameRemote;
use Sweetchuck\Git\Command\RestoreFiles;
use Sweetchuck\Git\Command\SetBranchUpstream;
use Sweetchuck\Git\Command\SetConfig;
use Sweetchuck\Git\Command\CommitStagedFiles;
use Sweetchuck\Git\Command\StageFiles;
use Sweetchuck\Git\Command\SwitchBranch;
use Sweetchuck\Git\Command\UnsetConfig;
use Sweetchuck\Git\Command\UpdateRemote;
use Sweetchuck\Git\Command\DeleteBranch;
use Sweetchuck\Git\Command\CreateBranch;
use Sweetchuck\Git\Command\MoveBranch;
use Sweetchuck\Git\Command\RemoveFiles;

interface CommandFactoryInterface
{
    // region processFactory
    public function getProcessFactory(): ?ProcessFactoryInterface;

    public function setProcessFactory(?ProcessFactoryInterface $processFactory): static;
    // endregion

    // region start
    public function createInitRepository(): InitRepository;

    public function createCloneRepository(): CloneRepository;
    // endregion

    // region config
    public function createUnsetConfig(): UnsetConfig;

    public function createGetConfigMultiple(): GetConfigMultiple;

    public function createSetConfig(): SetConfig;

    public function createGetConfigSingle(): GetConfigSingle;
    // endregion

    // region remote
    public function createGetRemotes(): GetRemotes;

    public function createAddRemote(): AddRemote;

    public function createRenameRemote(): RenameRemote;

    public function createUpdateRemote(): UpdateRemote;

    public function createRemoveRemote(): RemoveRemote;

    public function createPruneRemote(): PruneRemote;
    // endregion

    // region branch
    public function createGetBranches(): GetBranches;

    public function createCreateBranch(): CreateBranch;

    public function createMoveBranch(): MoveBranch;

    public function createSetBranchUpstream(): SetBranchUpstream;

    public function createDeleteBranch(): DeleteBranch;
    // endregion

    public function createSwitchBranch(): SwitchBranch;

    // region tag
    public function createGetTags(): GetTags;

    public function createCreateTag(): CreateTag;

    public function createDeleteTag(): DeleteTag;
    // endregion

    /**
     * Represents the "git status" command.
     */
    public function createGetStatus(): GetStatus;

    public function createGetCommits(): GetCommits;

    /**
     * Represents the "git ls-files" command.
     */
    public function createGetFiles(): GetFiles;

    /**
     * Represents the "git show [ref]:<filePath>" command.
     */
    public function createGetFileContent(): GetFileContent;

    public function createGetChangedFiles(): GetChangedFiles;

    /**
     * Represents the "git add" command.
     */
    public function createStageFiles(): StageFiles;

    /**
     * Represents the "git mv" command.
     */
    public function createMoveFiles(): MoveFiles;

    /**
     * Represents the "git restore" command.
     */
    public function createRestoreFiles(): RestoreFiles;

    /**
     * Represents the "git rm" command.
     */
    public function createRemoveFiles(): RemoveFiles;

    /**
     * Represents the "git diff --cached --name-status" command.
     */
    public function createGetStagedFiles(): GetStagedFiles;

    /**
     * Represents the "git commit --message" command.
     */
    public function createCommitStagedFiles(): CommitStagedFiles;

    /**
     * Represents the "git grep" command.
     */
    public function createGrepFiles(): GrepFiles;
}
