<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Sweetchuck\Git\Command\CliCommandInterface;

/**
 * @phpstan-import-type SweetchuckGitCommandAddRemoteProperties from \Sweetchuck\Git\Phpstan
 */
class Repository
{
    protected string $gitDir = '';

    protected bool $isBare = false;

    public function isBare(): bool
    {
        return $this->isBare;
    }

    public function getTopLevel(): string
    {
        return $this->topLevel;
    }

    /**
     * @param string $topLevel
     *   The top-level directory of a valid Git repository.
     *   If it has a ".git" direct descendant (file or directory), then it is
     *   assumed to be a working copy repository.
     *   Otherwise, it is assumed to be a bare repository.
     */
    public function __construct(
        protected string $topLevel,
        protected CommandFactoryInterface $commandFactory,
    ) {
        $this->initGitDir();
    }

    protected function initGitDir(): static
    {
        $gitDir = $this->topLevel . '/.git';
        if (is_dir($gitDir)) {
            // @todo Check directory content.
            $this->gitDir = $gitDir;
            $this->isBare = false;

            return $this;
        }

        if (is_file($gitDir)) {
            // @todo Error handling.
            $this->gitDir = trim(file_get_contents($gitDir) ?: '');
            $this->isBare = false;

            return $this;
        }

        $this->gitDir = $this->topLevel;
        $this->isBare = true;

        return $this;
    }

    /**
     * @phpstan-template TCommandClass of \Sweetchuck\Git\Command\CliCommandInterface
     *
     * @phpstan-param class-string<TCommandClass> $class
     *
     * @phpstan-return TCommandClass
     */
    public function command(string $class): CliCommandInterface
    {
        $properties = [];
        $this->populateCommonProperties($properties);

        return $this
            ->commandFactory
            ->createCommand($class)
            ->setProperties($properties);
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @return null|array<string, string>
     */
    public function getStatus(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetStatus()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @return null|array<string, string>
     */
    public function getCommits(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetCommits()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Get a list of changed files in the repository.
     *
     * @param array<string, mixed> $properties
     *   Properties for the GetChangedFiles command.
     *   Optional keys:
     *   - mergeBase: String value for the merge-base option.
     *   - noIndex: Boolean indicating whether to use the --no-index option.
     *   - diffFilter: String value for the diff-filter option.
     *   - filePathStyle: The style of file paths in the result.
     *   - commandArguments: Additional command arguments.
     *   - paths: Array of paths to limit the command to.
     *
     * @return ?array<string, mixed>
     *   An array of changed files, or null if the command failed.
     */
    public function getChangedFiles(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetChangedFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Get a list of staged files in the repository.
     *
     * @param array<string, mixed> $properties
     *   Properties for the GetStagedFiles command.
     *   Optional keys:
     *   - diffFilter: String value for the diff-filter option.
     *   - filePathStyle: The style of file paths in the result.
     *   - topLevel: The top level directory of the repository.
     *   - paths: Array of paths to limit the command to.
     *
     * @return ?array<string, mixed>
     *   An array of staged files, or null if the command failed.
     */
    public function getStagedFiles(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetStagedFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    // region branch
    /**
     * @param array<string, mixed> $properties
     *
     * @return ?array<string, mixed>
     */
    public function getBranches(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetBranches()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Create a new branch in this repository.
     *
     * @param array<string, mixed> $properties
     */
    public function createBranch(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createCreateBranch()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Delete one or more branches in this repository.
     *
     * @param array<string, mixed> $properties
     */
    public function deleteBranch(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createDeleteBranch()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Move (rename) a branch in this repository.
     *
     * @param array<string, mixed> $properties
     */
    public function moveBranch(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createMoveBranch()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Set upstream tracking for a branch in this repository.
     *
     * @param array<string, mixed> $properties
     *   Properties for the SetUpstreamTo command.
     *   Required keys:
     *   - upstream: The upstream branch to track (e.g., 'origin/main').
     *   Optional keys:
     *   - branch: The local branch to configure. If not specified, uses current branch.
     */
    public function setBranchUpstream(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createSetBranchUpstream()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    /**
     * @param array<string, mixed> $properties
     */
    public function switchBranch(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createSwitchBranch()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    // region tag
    /**
     * @param array<string, mixed> $properties
     *
     * @return ?array<string, mixed>
     */
    public function getTags(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetTags()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Create a new tag in this repository.
     *
     * @param array<string, mixed> $properties
     */
    public function createTag(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createCreateTag()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Delete one or more tags in this repository.
     *
     * @param array<string, mixed> $properties
     */
    public function deleteTag(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createDeleteTag()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    // region symbolic-ref
    /**
     * @param array<string, mixed> $properties
     *
     * @return null|array<string, mixed>
     */
    public function readSymbolicRef(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createReadSymbolicRef()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function upsertSymbolicRef(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createUpsertSymbolicRef()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function deleteSymbolicRef(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createDeleteSymbolicRef()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    // region remote
    // region remote CRUD
    /**
     * @param array<string, mixed> $properties
     *
     * @return ?array<string, mixed>
     */
    public function getRemotes(array $properties): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetRemotes()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Add a new remote to the repository.
     *
     * @phpstan-param SweetchuckGitCommandAddRemoteProperties $properties
     *   Properties for the AddRemote command.
     *   Required keys:
     *   - name: The name of the remote to add.
     *   - url: The URL of the remote repository.
     */
    public function addRemote(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createAddRemote()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Rename a remote in the repository.
     *
     * @param array<string, mixed> $properties
     *   Properties for the RenameRemote command.
     *   Required keys:
     *   - oldName: The name of the remote to rename.
     *   - newName: The new name.
     */
    public function renameRemote(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createRenameRemote()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Update a remote's URL or other properties.
     *
     * @param array<string, mixed> $properties
     *   Properties for the UpdateRemote command.
     *   Required keys:
     *   - name: The name of the remote to update.
     *   Optional keys:
     *   - url: The new URL for the remote.
     *   - addUrl: Additional URL to add to the remote.
     *   - deleteUrl: URL to delete from the remote.
     *   - pushUrl: The push URL for the remote.
     */
    public function updateRemote(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createUpdateRemote()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Remove a remote from the repository.
     *
     * @param array<string, mixed> $properties
     *   Properties for the RemoveRemote command.
     *   Required keys:
     *   - name: The name of the remote to remove.
     */
    public function removeRemote(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createRemoveRemote()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    // region remote get-url
    /**
     * @param array<string, mixed> $properties
     *
     * @return array<string>
     */
    public function getRemoteFetchUrls(array $properties): array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetRemoteFetchUrls()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $result->artifacts['urls'] ?? [];
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @return array<string>
     */
    public function getRemotePushUrls(array $properties): array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetRemotePushUrls()
            ->setProperties($properties)
            ->execute();

        $this->assertOutcome($result, [0]);

        return $result->artifacts['urls'] ?? [];
    }
    // endregion

    // region remote set-url
    /**
     * @param array<string, mixed> $properties
     */
    public function setRemoteFetchUrl(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createSetRemoteFetchUrl()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function addRemoteFetchUrl(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createAddRemoteFetchUrl()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function addRemotePushUrl(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createAddRemotePushUrl()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setRemotePushUrl(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createSetRemotePushUrl()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function deleteRemoteFetchUrl(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createDeleteRemoteFetchUrl()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function deleteRemotePushUrl(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createDeleteRemotePushUrl()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    /**
     * Prune a remote by removing stale remote-tracking branches.
     *
     * @param array<string, mixed> $properties
     *   Properties for the PruneRemote command.
     *   Required keys:
     *   - name: The name of the remote to prune.
     *   Optional keys:
     *   - dryRun: Boolean indicating whether to perform a dry run.
     */
    public function pruneRemote(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createPruneRemote()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     *   - add: Boolean.
     *   - name: The name of the remote.
     *   - branch: The name of the branch.
     */
    public function setRemoteBranches(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createSetRemoteBranches()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    // region config
    /**
     * Get a single configuration value.
     *
     * @param array<string, mixed> $properties
     *   Properties for the GetConfigSingle command.
     *   Required keys:
     *   - name: The name of the configuration variable to get.
     *   Optional keys:
     *   - configScope: The scope of the configuration (local, global, system).
     *
     * @return ?array<string, mixed>
     *   The configuration value, or null if the command failed.
     */
    public function getConfigSingle(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetConfigSingle()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Get multiple configuration values.
     *
     * @param array<string, mixed> $properties
     *   Properties for the GetConfigMultiple command.
     *   Optional keys:
     *   - regexp: String regular expression to match configuration names.
     *   - configScope: The scope of the configuration (local, global, system).
     *
     * @return ?array<string, mixed>
     *   An array of configuration values, or null if the command failed.
     */
    public function getConfigMultiple(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetConfigMultiple()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Set a configuration variable.
     *
     * @param array<string, mixed> $properties
     *   Properties for the SetConfig command.
     *   Required keys:
     *   - name: The name of the configuration variable to set.
     *   - value: The value to set.
     *   Optional keys:
     *   - configScope: The scope of the configuration (local, global, system).
     */
    public function setConfig(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createSetConfig()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Unset a configuration variable.
     *
     * @param array<string, mixed> $properties
     *   Properties for the UnsetConfig command.
     *   Required keys:
     *   - name: The name of the configuration variable to unset.
     *   Optional keys:
     *   - configScope: The scope of the configuration (local, global, system).
     */
    public function unsetConfig(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createUnsetConfig()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }
    // endregion

    /**
     * Represents the "git add" command.
     *
     * @param array<string, mixed> $properties
     */
    public function stageFiles(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createStageFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Represents the "git mv" command.
     *
     * @param array<string, mixed> $properties
     */
    public function moveFiles(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createMoveFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Represents the "git rm" command.
     *
     * @param array<string, mixed> $properties
     */
    public function removeFiles(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createRemoveFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Represents the "git restore" command.
     *
     * @param array<string, mixed> $properties
     */
    public function restoreFiles(array $properties): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createRestoreFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * Get the content of a file from the repository.
     *
     * @param array<string, mixed> $properties
     *   Properties for the GetFileContent command.
     *   Required keys:
     *   - file: The path to the file to get content from.
     *   Optional keys:
     *   - revision: The revision/commit to get the file from.
     *
     * @return ?array<string, mixed>
     *   The file content, or null if the command failed.
     */
    public function getFileContent(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetFileContent()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Runs `git ls-files`.
     *
     * @param array<string, mixed> $properties
     *   Properties for the GetFiles command.
     *   Optional keys:
     *   - cached: Boolean indicating whether to show cached files.
     *   - others: Boolean indicating whether to show untracked files.
     *   - ignored: Boolean indicating whether to show ignored files.
     *   - stage: Boolean indicating whether to show stage information.
     *   - unmerged: Boolean indicating whether to show unmerged files.
     *   - killed: Boolean indicating whether to show killed files.
     *   - directory: Boolean indicating whether to show directories.
     *   - modified: Boolean indicating whether to show modified files.
     *   - deleted: Boolean indicating whether to show deleted files.
     *   - excludeStandard: Boolean indicating whether to exclude standard ignored files.
     *   - excludePerDirectory: String path to per-directory exclude file.
     *   - exclude: Array of exclude patterns.
     *   - excludeFrom: String path to exclude file.
     *
     * @return ?array<string, mixed>
     *   An array of files, or null if the command failed.
     */
    public function getFiles(array $properties = []): ?array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGetFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $result->artifacts;
    }

    /**
     * Runs `git commit`.
     *
     * @param array<string, mixed> $properties
     *   Properties for the CommitStagedFiles command.
     *   Optional keys:
     *   - message: The commit message.
     *   - author: The author of the commit.
     *   - date: The date of the commit.
     *   - amend: Boolean indicating whether to amend the last commit.
     *   - signoff: Boolean indicating whether to add a Signed-off-by line.
     *   - noVerify: Boolean indicating whether to skip pre-commit and commit-msg hooks.
     *   - allowEmpty: Boolean indicating whether to allow empty commits.
     *   - edit: Boolean indicating whether to open an editor for the commit message.
     *   - file: String path to a file containing the commit message.
     *   - template: String path to a template file for the commit message.
     *   - paths: Array of paths to commit.
     */
    public function commitStagedFiles(array $properties = []): static
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createCommitStagedFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0]);

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @return array<string, mixed>
     */
    public function grepFiles(array $properties = []): array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createGrepFiles()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0, 1]);

        return $result->artifacts;
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @return array<string, mixed>
     */
    public function checkIgnore(array $properties = []): array
    {
        $result = $this
            ->populateCommonProperties($properties)
            ->commandFactory
            ->createCheckIgnore()
            ->setProperties($properties)
            ->execute();
        $this->assertOutcome($result, [0, 1]);

        return $result->artifacts;
    }

    /**
     * @param array<int<0, 255>> $allowedExitCodes
     */
    protected function assertOutcome(
        CommandResult $result,
        array $allowedExitCodes,
    ): void {
        if (!$allowedExitCodes
            || in_array($result->process->getExitCode(), $allowedExitCodes)
        ) {
            return;
        }

        throw new \RuntimeException(sprintf(
            <<<'TEXT'
                --== command ==--
                %s

                Exit code: %d

                --== stdOutput ==--
                %s

                --== stdError ==--:
                %s
                TEXT,
            $result->process->getCommandLine(),
            $result->process->getExitCode(),
            $result->process->getOutput(),
            $result->process->getErrorOutput(),
        ));
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function populateCommonProperties(array &$properties): static
    {
        $properties['cwd'] = $this->topLevel;
        $properties['topLevel'] = $this->topLevel;

        return $this;
    }
}
