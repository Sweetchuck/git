<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Sweetchuck\Git\Command\AddRemote;
use Sweetchuck\Git\Command\AddRemoteFetchUrl;
use Sweetchuck\Git\Command\AddRemotePushUrl;
use Sweetchuck\Git\Command\CheckIgnore;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\CloneRepository;
use Sweetchuck\Git\Command\CommitStagedFiles;
use Sweetchuck\Git\Command\CreateTag;
use Sweetchuck\Git\Command\DeleteRemoteFetchUrl;
use Sweetchuck\Git\Command\DeleteRemotePushUrl;
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
use Sweetchuck\Git\Command\RemoveRemote;
use Sweetchuck\Git\Command\RenameRemote;
use Sweetchuck\Git\Command\RestoreFiles;
use Sweetchuck\Git\Command\SetConfig;
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
use Sweetchuck\Git\Command\SetBranchUpstream;
use Sweetchuck\Git\Command\RemoveFiles;

/**
 * @todo Central place for "new FooCommand();"
 * @todo Create command instances with dependency injection support.
 */
class CommandFactory implements CommandFactoryInterface
{

    // region processFactory
    protected ?ProcessFactoryInterface $processFactory = null;

    public function getProcessFactory(): ?ProcessFactoryInterface
    {
        return $this->processFactory;
    }

    public function setProcessFactory(?ProcessFactoryInterface $processFactory): static
    {
        $this->processFactory = $processFactory;

        return $this;
    }
    // endregion

    protected function prepareCommand(CliCommandInterface $command): static
    {
        $command->setProcessFactory($this->getProcessFactory());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createCommand(string $class): CliCommandInterface
    {
        $command = new $class();
        $this->prepareCommand($command);

        return $command;
    }

    // region start
    /**
     * {@inheritdoc}
     */
    public function createInitRepository(): InitRepository
    {
        $command = new InitRepository();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createCloneRepository(): CloneRepository
    {
        $command = new CloneRepository();
        $this->prepareCommand($command);

        return $command;
    }
    // endregion

    // region config
    /**
     * {@inheritdoc}
     */
    public function createUnsetConfig(): UnsetConfig
    {
        $command = new UnsetConfig();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetConfigMultiple(): GetConfigMultiple
    {
        $command = new GetConfigMultiple();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createSetConfig(): SetConfig
    {
        $command = new SetConfig();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetConfigSingle(): GetConfigSingle
    {
        $command = new GetConfigSingle();
        $this->prepareCommand($command);

        return $command;
    }
    // endregion

    // region remote
    /**
     * {@inheritdoc}
     */
    public function createGetRemotes(): GetRemotes
    {
        $command = new GetRemotes();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createAddRemote(): AddRemote
    {
        $command = new AddRemote();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createRenameRemote(): RenameRemote
    {
        $command = new RenameRemote();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createUpdateRemote(): UpdateRemote
    {
        $command = new UpdateRemote();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createRemoveRemote(): RemoveRemote
    {
        $command = new RemoveRemote();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createPruneRemote(): PruneRemote
    {
        $command = new PruneRemote();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createSetRemoteBranches(): SetRemoteBranches
    {
        $command = new SetRemoteBranches();
        $this->prepareCommand($command);

        return $command;
    }

    // region remote get-set-url
    /**
     * {@inheritdoc}
     */
    public function createGetRemoteFetchUrls(): GetRemoteFetchUrls
    {
        $command = new GetRemoteFetchUrls();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetRemotePushUrls(): GetRemotePushUrls
    {
        $command = new GetRemotePushUrls();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createSetRemoteFetchUrl(): SetRemoteFetchUrl
    {
        $command = new SetRemoteFetchUrl();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createSetRemotePushUrl(): SetRemotePushUrl
    {
        $command = new SetRemotePushUrl();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createAddRemoteFetchUrl(): AddRemoteFetchUrl
    {
        $command = new AddRemoteFetchUrl();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createAddRemotePushUrl(): AddRemotePushUrl
    {
        $command = new AddRemotePushUrl();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteRemoteFetchUrl(): DeleteRemoteFetchUrl
    {
        $command = new DeleteRemoteFetchUrl();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteRemotePushUrl(): DeleteRemotePushUrl
    {
        $command = new DeleteRemotePushUrl();
        $this->prepareCommand($command);

        return $command;
    }
    // endregion
    // endregion

    // region branch
    /**
     * {@inheritdoc}
     */
    public function createGetBranches(): GetBranches
    {
        $command = new GetBranches();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createCreateBranch(): CreateBranch
    {
        $command = new CreateBranch();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createMoveBranch(): MoveBranch
    {
        $command = new MoveBranch();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createSetBranchUpstream(): SetBranchUpstream
    {
        $command = new SetBranchUpstream();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteBranch(): DeleteBranch
    {
        $command = new DeleteBranch();
        $this->prepareCommand($command);

        return $command;
    }
    // endregion

    /**
     * {@inheritdoc}
     */
    public function createSwitchBranch(): SwitchBranch
    {
        $command = new SwitchBranch();
        $this->prepareCommand($command);

        return $command;
    }

    // region tag
    /**
     * {@inheritdoc}
     */
    public function createGetTags(): GetTags
    {
        $command = new GetTags();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createCreateTag(): CreateTag
    {
        $command = new CreateTag();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteTag(): DeleteTag
    {
        $command = new DeleteTag();
        $this->prepareCommand($command);

        return $command;
    }
    // endregion

    /**
     * {@inheritdoc}
     */
    public function createGetStatus(): GetStatus
    {
        $command = new GetStatus();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetCommits(): GetCommits
    {
        $command = new GetCommits();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetFiles(): GetFiles
    {
        $command = new GetFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetFileContent(): GetFileContent
    {
        $command = new GetFileContent();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetChangedFiles(): GetChangedFiles
    {
        $command = new GetChangedFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createStageFiles(): StageFiles
    {
        $command = new StageFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGetStagedFiles(): GetStagedFiles
    {
        $command = new GetStagedFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createCommitStagedFiles(): CommitStagedFiles
    {
        $command = new CommitStagedFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createMoveFiles(): MoveFiles
    {
        $command = new MoveFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createRestoreFiles(): RestoreFiles
    {
        $command = new RestoreFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createRemoveFiles(): RemoveFiles
    {
        $command = new RemoveFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createGrepFiles(): GrepFiles
    {
        $command = new GrepFiles();
        $this->prepareCommand($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function createCheckIgnore(): CheckIgnore
    {
        $command = new CheckIgnore();
        $this->prepareCommand($command);

        return $command;
    }
}
