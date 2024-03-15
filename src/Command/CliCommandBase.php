<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\CliCommandBuilder;
use Sweetchuck\Git\CommandResult;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\ProcessFactory;
use Sweetchuck\Git\ProcessFactoryInterface;

/**
 * @todo Handle the "allowedExitCodes", \Sweetchuck\Git\Repository::assertOutcome().
 *
 * @phpstan-import-type SweetchuckGitCommandProperties from \Sweetchuck\Git\Phpstan
 */
class CliCommandBase extends CommandBase implements CliCommandInterface
{

    /**
     * @var array<string, mixed>
     */
    protected array $properties = [];

    protected function initProperties(): static
    {
        $this->properties = [
            'workingDirectory' => null,
            'envVars' => [],
            'executable' => 'git',
            'globalOptions' => [
                // @todo Global options:
                // --namespace=<name>
                // --pager=?
                // --paginate
                // --no-pager
                // -c <name>=<value>
                // --config-env=<name>=<envvar>
                // --exec-path[=<path>]
                // --html-path
                // --man-path
                // --info-path
                // --no-replace-objects
                // --no-lazy-fetch
                // --no-optional-locks
                // --no-advice
                // --bare
                'gitDir' => [
                    'type' => 'value:string-required',
                    'name' => '--git-dir',
                    'value' => null,
                ],
                'workTree' => [
                    'type' => 'value:string-required',
                    'name' => '--work-tree',
                    'value' => null,
                ],
                'cwd' => [
                    'type' => 'value:string-required',
                    'name' => '-C',
                    'value' => null,
                ],
            ],
            'command' => [],
            'commandOptions' => [],
            'commandArguments' => [],
            'extraArguments' => [],
        ];

        return $this;
    }

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
        if ($this->processFactory === null) {
            $this->setProcessFactory(new ProcessFactory());
        }

        return $this->processFactory;
    }
    // endregion

    // region workingDirectory
    public function getWorkingDirectory(): ?string
    {
        return $this->properties['workingDirectory'];
    }

    /**
     * This will be passed as the $cwd argument of the \Symfony\Component\Process\Process::__construct().
     */
    public function setWorkingDirectory(?string $workingDirectory): static
    {
        $this->properties['workingDirectory'] = $workingDirectory;

        return $this;
    }
    // endregion

    // region envVars
    /**
     * @return ?array<string, string>
     */
    public function getEnvVars(): ?array
    {
        return $this->properties['envVars'];
    }

    /**
     * @param ?array<string, string> $envVars
     */
    public function setEnvVars(?array $envVars): static
    {
        $this->properties['envVars'] = $envVars;

        return $this;
    }
    // endregion

    // region gitExecutable
    public function getGitExecutable(): string
    {
        return $this->properties['executable'];
    }

    public function setGitExecutable(string $path): static
    {
        $this->properties['executable'] = $path;

        return $this;
    }
    // endregion

    //region gitDir
    public function getGitDir(): ?string
    {
        return $this->properties['globalOptions']['gitDir']['value'];
    }

    public function setGitDir(?string $gitDir): static
    {
        $this->properties['globalOptions']['gitDir']['value'] = $gitDir;

        return $this;
    }
    //endregion

    //region workTree
    public function getWorkTree(): ?string
    {
        return $this->properties['globalOptions']['workTree']['value'];
    }

    public function setWorkTree(?string $dir): static
    {
        $this->properties['globalOptions']['workTree']['value'] = $dir;

        return $this;
    }
    //endregion

    //region cwd
    public function getCwd(): ?string
    {
        return $this->properties['globalOptions']['cwd']['value'];
    }

    /**
     * Same as the -C option.
     *
     * @todo Support for multiple values.
     */
    public function setCwd(?string $dir): static
    {
        $this->properties['globalOptions']['cwd']['value'] = $dir;

        return $this;
    }
    //endregion

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

    protected function getCliCommandBuilder(): CliCommandBuilder
    {
        return new CliCommandBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties): static
    {
        if (array_key_exists('processFactory', $properties)) {
            $this->setProcessFactory($properties['processFactory']);
        }

        if (array_key_exists('workingDirectory', $properties)) {
            $this->setWorkingDirectory($properties['workingDirectory']);
        }

        if (array_key_exists('envVars', $properties)) {
            $this->setEnvVars($properties['envVars']);
        }

        if (array_key_exists('gitExecutable', $properties)) {
            $this->setGitExecutable($properties['gitExecutable']);
        }

        if (array_key_exists('gitDir', $properties)) {
            $this->setGitDir($properties['gitDir']);
        }

        if (array_key_exists('workTree', $properties)) {
            $this->setWorkTree($properties['workTree']);
        }

        if (array_key_exists('cwd', $properties)) {
            $this->setCwd($properties['cwd']);
        }

        if (array_key_exists('outcomeParser', $properties)) {
            $this->setOutcomeParser($properties['outcomeParser']);
        }

        return $this;
    }

    public function __construct()
    {
        $this->initProperties();
    }

    /**
     * {@inheritdoc}
     */
    public function getCliCommand(): array
    {
        return $this
            ->preGetCliCommand()
            ->getCliCommandBuilder()
            ->build($this->getFinalProperties());
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFinalProperties(): array
    {
        return $this->properties;
    }

    /**
     * @todo Remove this method. ::getFinalProperties is more flexible.
     */
    protected function preGetCliCommand(): static
    {
        return $this;
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
            $this->getOutputParserOptions(),
        );

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOutputParserOptions(): array
    {
        return [];
    }
}
