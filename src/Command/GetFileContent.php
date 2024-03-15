<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\OutcomeParser\RawOutputParser;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Represents the "git show '[ref]:<filePath>'" command.
 *
 * This command retrieves the content of a file at a specific commit,
 * or the content of a staged file when the commitHash is omitted.
 * `git show ':index.php'`.
 */
class GetFileContent extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['show'];
        $this->properties['commandArguments'] = [
            'object' => null,
        ];

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('commitHash', $properties)) {
            $this->setCommitHash($properties['commitHash']);
        }

        if (array_key_exists('filePath', $properties)) {
            $this->setFilePath($properties['filePath']);
        }

        return $this;
    }

    // region commitHash
    protected ?string $commitHash = null;

    public function getCommitHash(): ?string
    {
        return $this->commitHash;
    }

    public function setCommitHash(?string $commitHash): static
    {
        $this->commitHash = $commitHash;
        $this->updateObjectArgument();

        return $this;
    }
    // endregion

    // region filePath
    protected ?string $filePath = null;

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;
        $this->updateObjectArgument();

        return $this;
    }
    // endregion

    /**
     * Updates the object argument based on commitHash and filePath.
     */
    protected function updateObjectArgument(): void
    {
        $isValid = $this->filePath !== null;
        $this->properties['commandArguments']['object'] = $isValid
            ? sprintf('%s:%s', $this->commitHash, $this->filePath)
            : null;
    }

    protected function getDefaultOutcomeParser(): ?OutcomeParserInterface
    {
        return new RawOutputParser();
    }

    /**
     * {@inheritdoc}
     */
    protected function getOutputParserOptions(): array
    {
        return [
            'stdOutputKey' => 'content',
            'stdErrorKey' => null,
        ];
    }
}
