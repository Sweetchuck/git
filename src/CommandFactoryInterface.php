<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Sweetchuck\Git\Command\AddRemote;
use Sweetchuck\Git\Command\AddRemoteFetchUrl;
use Sweetchuck\Git\Command\AddRemotePushUrl;
use Sweetchuck\Git\Command\CheckIgnore;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\CloneRepository;
use Sweetchuck\Git\Command\CreateTag;
use Sweetchuck\Git\Command\DeleteRemoteFetchUrl;
use Sweetchuck\Git\Command\DeleteRemotePushUrl;
use Sweetchuck\Git\Command\DeleteSymbolicRef;
use Sweetchuck\Git\Command\DeleteTag;
use Sweetchuck\Git\Command\GetBranches;
use Sweetchuck\Git\Command\GetChangedFiles;
use Sweetchuck\Git\Command\GetCommits;
use Sweetchuck\Git\Command\GetConfigMultiple;
use Sweetchuck\Git\Command\GetConfigSingle;
use Sweetchuck\Git\Command\GetFileContent;
use Sweetchuck\Git\Command\GetFiles;
use Sweetchuck\Git\Command\GetRemoteFetchUrls;
use Sweetchuck\Git\Command\GetRemotePushUrls;
use Sweetchuck\Git\Command\GetRemotes;
use Sweetchuck\Git\Command\GetStagedFiles;
use Sweetchuck\Git\Command\GetStatus;
use Sweetchuck\Git\Command\GetTags;
use Sweetchuck\Git\Command\GrepFiles;
use Sweetchuck\Git\Command\InitRepository;
use Sweetchuck\Git\Command\MoveFiles;
use Sweetchuck\Git\Command\PruneRemote;
use Sweetchuck\Git\Command\ReadSymbolicRef;
use Sweetchuck\Git\Command\RemoveRemote;
use Sweetchuck\Git\Command\RenameRemote;
use Sweetchuck\Git\Command\RestoreFiles;
use Sweetchuck\Git\Command\SetBranchUpstream;
use Sweetchuck\Git\Command\SetConfig;
use Sweetchuck\Git\Command\CommitStagedFiles;
use Sweetchuck\Git\Command\SetRemoteBranches;
use Sweetchuck\Git\Command\SetRemoteFetchUrl;
use Sweetchuck\Git\Command\SetRemotePushUrl;
use Sweetchuck\Git\Command\StageFiles;
use Sweetchuck\Git\Command\SwitchBranch;
use Sweetchuck\Git\Command\UnsetConfig;
use Sweetchuck\Git\Command\UpdateRemote;
use Sweetchuck\Git\Command\DeleteBranch;
use Sweetchuck\Git\Command\CreateBranch;
use Sweetchuck\Git\Command\MoveBranch;
use Sweetchuck\Git\Command\RemoveFiles;
use Sweetchuck\Git\Command\UpsertSymbolicRef;

interface CommandFactoryInterface
{
    // region processFactory
    public function getProcessFactory(): ?ProcessFactoryInterface;

    public function setProcessFactory(?ProcessFactoryInterface $processFactory): static;
    // endregion

    /**
     * @phpstan-template TCommandClass of \Sweetchuck\Git\Command\CliCommandInterface
     *
     * @phpstan-param class-string<TCommandClass> $class
     *
     * @phpstan-return TCommandClass
     */
    public function createCommand(string $class): CliCommandInterface;

    // region start
    /**
     * Represents the "git init" command.
     */
    public function createInitRepository(): InitRepository;

    /**
     * Represents the "git clone" command.
     */
    public function createCloneRepository(): CloneRepository;
    // endregion

    // region config
    /**
     * Represents the "git config unset" command.
     */
    public function createUnsetConfig(): UnsetConfig;

    /**
     * Represents the "git config list" command.
     */
    public function createGetConfigMultiple(): GetConfigMultiple;

    /**
     * Represents the "git config set" command.
     */
    public function createSetConfig(): SetConfig;

    /**
     * Represents the "git config get" command.
     */
    public function createGetConfigSingle(): GetConfigSingle;
    // endregion

    // region remote
    // region remote CRUD
    /**
     * Represents the "git remote --verbose" command.
     */
    public function createGetRemotes(): GetRemotes;

    /**
     * Represents the "git remote add" command.
     */
    public function createAddRemote(): AddRemote;

    /**
     * Represents the "git remote rename" command.
     */
    public function createRenameRemote(): RenameRemote;

    /**
     * Represents the "git remote update" command.
     */
    public function createUpdateRemote(): UpdateRemote;

    /**
     * Represents the "git remote remove" command.
     */
    public function createRemoveRemote(): RemoveRemote;
    // endregion

    // region remote URL CRUD
    /**
     * Represents the "git remote get-url --all <remoteName>" command.
     */
    public function createGetRemoteFetchUrls(): GetRemoteFetchUrls;

    /**
     * Represents the "git remote get-url --push --all <remoteName>" command.
     */
    public function createGetRemotePushUrls(): GetRemotePushUrls;

    /**
     * Represents the "git remote set-url <remoteName> <url> [oldUrl]" command.
     */
    public function createSetRemoteFetchUrl(): SetRemoteFetchUrl;

    /**
     * Represents the "git remote set-url --push" command.
     */
    public function createSetRemotePushUrl(): SetRemotePushUrl;

    /**
     * Represents the "git remote set-url --add" command.
     */
    public function createAddRemoteFetchUrl(): AddRemoteFetchUrl;

    /**
     * Represents the "git remote set-url --push --add" command.
     */
    public function createAddRemotePushUrl(): AddRemotePushUrl;

    /**
     * Represents the "git remote set-url --delete" command.
     */
    public function createDeleteRemoteFetchUrl(): DeleteRemoteFetchUrl;

    /**
     * Represents the "git remote set-url --push --delete" command.
     */
    public function createDeleteRemotePushUrl(): DeleteRemotePushUrl;
    // endregion

    /**
     * Represents the "git remote prune" command.
     */
    public function createPruneRemote(): PruneRemote;

    /**
     * Represents the "git remote set-branches" command.
     */
    public function createSetRemoteBranches(): SetRemoteBranches;
    // endregion

    // region branch
    /**
     * Represents the "git branch --verbose" command.
     */
    public function createGetBranches(): GetBranches;

    /**
     * Represents the "git branch <branch-name>" command.
     */
    public function createCreateBranch(): CreateBranch;

    /**
     * Represents the "git branch --move" command.
     */
    public function createMoveBranch(): MoveBranch;

    /**
     * Represents the "git branch --set-upstream-to" command.
     */
    public function createSetBranchUpstream(): SetBranchUpstream;

    /**
     * Represents the "git branch --delete" command.
     */
    public function createDeleteBranch(): DeleteBranch;
    // endregion

    /**
     * Represents the "git switch" command.
     */
    public function createSwitchBranch(): SwitchBranch;

    // region tag
    /**
     * Represents the "git tag --verbose" command.
     */
    public function createGetTags(): GetTags;

    /**
     * Represents the "git tag <my-tag-01>" command.
     */
    public function createCreateTag(): CreateTag;

    /**
     * Represents the "git tag --delete" command.
     */
    public function createDeleteTag(): DeleteTag;
    // endregion

    // region symbolic-ref
    /**
     * Represents the "git symbolic-ref <name>" command.
     */
    public function createReadSymbolicRef(): ReadSymbolicRef;

    /**
     * Represents the "git symbolic-ref <name> <pointsTo>" command.
     */
    public function createUpsertSymbolicRef(): UpsertSymbolicRef;

    /**
     * Represents the "git symbolic-ref --delete <name>" command.
     */
    public function createDeleteSymbolicRef(): DeleteSymbolicRef;
    // endregion

    /**
     * Represents the "git status" command.
     */
    public function createGetStatus(): GetStatus;

    /**
     * Represents the "git log" command.
     */
    public function createGetCommits(): GetCommits;

    /**
     * Represents the "git ls-files" command.
     */
    public function createGetFiles(): GetFiles;

    /**
     * Represents the "git show [ref]:<filePath>" command.
     */
    public function createGetFileContent(): GetFileContent;

    /**
     * Represents the "git diff --name-only --cached" command.
     */
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

    /**
     * Represents the "git check-ignore" command.
     */
    public function createCheckIgnore(): CheckIgnore;
}
