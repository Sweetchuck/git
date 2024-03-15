<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\CommandResult;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\ProcessFactory;
use Sweetchuck\Git\ProcessFactoryInterface;

class CliCommandBase extends CommandBase implements CliCommandInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $cliOptions = [];

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

    protected function getFinalProcessFactory(): ProcessFactoryInterface
    {
        return $this->processFactory ?? new ProcessFactory();
    }
    // endregion

    // region workingDirectory
    protected ?string $workingDirectory = null;

    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }

    public function setWorkingDirectory(?string $workingDirectory): static
    {
        $this->workingDirectory = $workingDirectory;

        return $this;
    }
    // endregion

    // region envVars
    /**
     * @var array<string, string>
     */
    protected ?array $envVars = null;

    /**
     * @return ?array<string, string>
     */
    public function getEnvVars(): ?array
    {
        return $this->envVars;
    }

    /**
     * @param ?array<string, string> $envVars
     */
    public function setEnvVars(?array $envVars): static
    {
        $this->envVars = $envVars;

        return $this;
    }
    // endregion

    // region gitExecutable
    protected string $gitExecutable = 'git';

    public function getGitExecutable(): string
    {
        return $this->gitExecutable;
    }

    public function setGitExecutable(string $path): static
    {
        $this->gitExecutable = $path;

        return $this;
    }
    // endregion

    // @todo Global options:
    // --git-dir=<path>
    // --work-tree=<path>
    // --namespace=<name>
    // --pager=?
    // --paginate
    // --no-pager
    // -C <path>
    // -c <name>=<value>
    // --exec-path[=<path>]
    // --html-path
    // --man-path
    // --info-path
    // --no-replace-objects
    // --no-lazy-fetch
    // --no-optional-locks
    // --no-advice
    // --bare
    // --config-env=<name>=<envvar>

    // region outcomeParser
    protected ?OutcomeParserInterface $outcomeParser = null;

    public function getOutcomeParser(): ?OutcomeParserInterface
    {
        return $this->outcomeParser;
    }

    public function setOutcomeParser(?OutcomeParserInterface $outcomeParser): static
    {
        $this->outcomeParser = $outcomeParser;

        return $this;
    }

    protected function getFinalOutcomeParser(): ?OutcomeParserInterface
    {
        return $this->getOutcomeParser() ?: $this->getDefaultOutcomeParser();
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return null;
    }
    // endregion

    public function setOptions(array $options): static
    {
        parent::setOptions($options);

        if (array_key_exists('processFactory', $options)) {
            $this->setProcessFactory($options['processFactory']);
        }

        if (array_key_exists('workingDirectory', $options)) {
            $this->setWorkingDirectory($options['workingDirectory']);
        }

        if (array_key_exists('envVars', $options)) {
            $this->setEnvVars($options['envVars']);
        }

        if (array_key_exists('gitExecutable', $options)) {
            $this->setGitExecutable($options['gitExecutable']);
        }

        if (array_key_exists('outcomeParser', $options)) {
            $this->setOutcomeParser($options['outcomeParser']);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCliCommand(): array
    {
        $command = [];
        $command[] = $this->getGitExecutable();

        return $command;
    }

    public function execute(): CommandResult
    {
        $process = $this
            ->getFinalProcessFactory()
            ->createProcess(
                $this->getCliCommand(),
                $this->getWorkingDirectory(),
                $this->getEnvVars(),
                null,
                null,
            );
        $process->run();

        $result = new CommandResult();
        $result->process = $process;
        $result->artifacts = $this->getFinalOutcomeParser()?->parse(
            $process->getExitCode(),
            $process->getOutput(),
            $process->getErrorOutput(),
        );

        return $result;
    }

    /**
     * @param array<string> $command
     */
    protected function addFlagToCommand(string $name, ?bool $value, array &$command): static
    {
        if ($value === true) {
            $command[] = "--$name";
        } elseif ($value === false) {
            $command[] = "--no-$name";
        }

        return $this;
    }

    /**
     * @param array<string> $command
     */
    protected function addValueOptionalToCommand(
        string $name,
        ?string $value,
        array &$command,
    ): static {
        if ($value === '') {
            $command[] = "--{$name}";
        } elseif ($value !== null) {
            $command[] = "--{$name}={$value}";
        }

        return $this;
    }

    /**
     * @param array<string>|array<string, bool> $values
     * @param array<string> $command
     */
    protected function addExtraArguments(array $values, array &$command): static
    {
        $first = reset($values);
        if (gettype($first) === 'boolean') {
            $values = array_keys($values, true);
        }

        if (!$values) {
            return $this;
        }

        if (!in_array('--', $command)) {
            $command[] = '--';
        }

        $command = array_merge($command, $values);

        return $this;
    }
}
