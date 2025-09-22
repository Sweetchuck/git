<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

class CliCommandExecutionException extends \RuntimeException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        protected CommandResult $result,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            $this->initMessage(),
            $this->result->process->getExitCode(),
            $previous,
        );
    }

    public function getResult(): CommandResult
    {
        return $this->result;
    }

    protected function initMessage(): string
    {
        return sprintf(
            <<<'TEXT'
                --== command ==--
                %s

                Exit code: %d

                --== stdOutput ==--
                %s

                --== stdError ==--:
                %s
                TEXT,
            $this->result->process->getCommandLine(),
            $this->result->process->getExitCode(),
            $this->result->process->getOutput(),
            $this->result->process->getErrorOutput(),
        );
    }
}
