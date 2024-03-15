<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentPathsTrait;
use Sweetchuck\Git\OutcomeParser\GetStatusParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * @phpstan-import-type SweetchuckGitIgnoredModes from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitUntrackedFilesModes from \Sweetchuck\Git\Phpstan
 */
class GetStatus extends CliCommandBase
{
    use ArgumentPathsTrait;

    // region renames
    protected ?bool $renames = null;

    public function getRenames(): ?bool
    {
        return $this->renames;
    }

    public function setRenames(?bool $renames): static
    {
        $this->renames = $renames;

        return $this;
    }
    // endregion

    // region findRenames
    protected ?int $findRenames = null;

    public function getFindRenames(): ?int
    {
        return $this->findRenames;
    }

    /**
     * @todo PhpStan range int<0, 100>.
     */
    public function setFindRenames(?int $findRenames): static
    {
        $this->findRenames = $findRenames;

        return $this;
    }
    // endregion

    // region ignored
    /**
     * @phpstan-var null|SweetchuckGitIgnoredModes
     */
    protected ?string $ignored = null;

    /**
     * @phpstan-return null|SweetchuckGitIgnoredModes
     */
    public function getIgnored(): ?string
    {
        return $this->ignored;
    }

    /**
     * @phpstan-param null|SweetchuckGitIgnoredModes $ignored
     */
    public function setIgnored(?string $ignored): static
    {
        $this->ignored = $ignored;

        return $this;
    }
    // endregion

    // region untrackedFiles
    /**
     * @var null|null|SweetchuckGitUntrackedFilesModes
     */
    protected ?string $untrackedFiles = null;

    /**
     * @phpstan-return null|SweetchuckGitUntrackedFilesModes
     */
    public function getUntrackedFiles(): ?string
    {
        return $this->untrackedFiles;
    }

    /**
     * @phpstan-param null|SweetchuckGitUntrackedFilesModes $untrackedFiles
     */
    public function setUntrackedFiles(?string $untrackedFiles): static
    {
        $this->untrackedFiles = $untrackedFiles;

        return $this;
    }
    // endregion

    // @todo --ignore-submodules

    /**
     * {@inheritdoc}
     *
     * @todo Phpstan type.
     */
    public function setOptions(array $options): static
    {
        parent::setOptions($options);

        if (array_key_exists('renames', $options)) {
            $this->setRenames($options['renames']);
        }

        if (array_key_exists('findRenames', $options)) {
            $this->setFindRenames($options['findRenames']);
        }

        if (array_key_exists('ignored', $options)) {
            $this->setIgnored($options['ignored']);
        }

        if (array_key_exists('untrackedFiles', $options)) {
            $this->setUntrackedFiles($options['untrackedFiles']);
        }

        if (array_key_exists('paths', $options)) {
            $this->setPaths($options['paths']);
        }

        return $this;
    }

    public function getCliCommand(): array
    {
        $command = parent::getCliCommand();
        $command[] = 'status';
        $command[] = '--porcelain';
        $command[] = '-z';

        $findRenames = $this->getFindRenames();
        if ($findRenames === 0) {
            $findRenames = '';
        } elseif ($findRenames !== null) {
            settype($findRenames, 'string');
        }

        $this
            ->addFlagToCommand('renames', $this->getRenames(), $command)
            ->addValueOptionalToCommand('find-renames', $findRenames, $command)
            ->addValueOptionalToCommand('ignored', $this->getIgnored(), $command)
            ->addValueOptionalToCommand('untracked-files', $this->getUntrackedFiles(), $command)
            ->addExtraArguments($this->getPaths(), $command);

        return $command;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new GetStatusParser();
    }
}
