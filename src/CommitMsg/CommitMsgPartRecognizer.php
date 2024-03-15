<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\CommitMsg;

use Sweetchuck\Utils\Comparer\ArrayValueComparer;

/**
 * General commit message part recognizer.
 *
 * Uses a regexp pattern to identify the part,
 * and a key template to replace the array key.
 *
 * This can be used only when the keyTemple produces a unique identifier.
 *
 * @phpstan-import-type SweetchuckGitCommitMsgPart from \Sweetchuck\Git\Phpstan
 */
class CommitMsgPartRecognizer
{

    // region keyTemplate
    protected string $keyTemplate = '';

    public function getKeyTemplate(): string
    {
        return $this->keyTemplate;
    }

    /**
     * @param string $keyTemplate
     *   Template for the new array key when a part matches to the ::$checkerPattern.
     *   Named groups from the ::$checkerPattern can be used as template variables.
     *   Example: "pamald:report:{{ lockFilePath }}".
     */
    public function setKeyTemplate(string $keyTemplate): static
    {
        $this->keyTemplate = $keyTemplate;

        return $this;
    }
    // endregion

    // region checkerPattern
    protected string $checkerPattern = '';

    public function getCheckerPattern(): string
    {
        return $this->checkerPattern;
    }

    /**
     * @param string $checkerPattern
     *   Regexp pattern to check that if a part is a Pamald report or not.
     *   Example: "/^Changes in (?P<lockFilePath>.+?):\n/".
     */
    public function setCheckerPattern(string $checkerPattern): static
    {
        $this->checkerPattern = $checkerPattern;

        return $this;
    }
    // endregion

    /**
     * @param array<string, mixed> $options
     *
     * @todo ArrayShape.
     */
    public function setOptions(array $options): static
    {
        if (array_key_exists('keyTemplate', $options)) {
            $this->setKeyTemplate($options['keyTemplate']);
        }

        if (array_key_exists('checkerPattern', $options)) {
            $this->setCheckerPattern($options['checkerPattern']);
        }

        return $this;
    }

    /**
     * @phpstan-param array<string, SweetchuckGitCommitMsgPart> $parts
     *
     * @throws \LogicException
     */
    public function __invoke(array &$parts): void
    {
        $this->modify($parts);
    }

    /**
     * @phpstan-param array<string, SweetchuckGitCommitMsgPart> $parts
     *
     * @throws \LogicException
     */
    public function modify(array &$parts): void
    {
        $checkerPattern = $this->getCheckerPattern();
        if (!$checkerPattern) {
            throw new \LogicException('Checker pattern is not set', 1);
        }

        $keyTemplate = $this->getKeyTemplate();
        if (!$keyTemplate) {
            throw new \LogicException('Key template is not set', 2);
        }

        $changed = false;
        foreach (array_keys($parts) as $key) {
            if (!$this->isUnknown($key)) {
                continue;
            }

            $matches = [];
            preg_match($checkerPattern, $parts[$key]['content'], $matches);
            if (!$matches) {
                continue;
            }

            $replacementPairs = $this->convertMatchesToPairs($matches);
            $newKey = $this->getNewKey($key, $replacementPairs);
            $parts[$newKey] = $this->getNewValue($parts[$key], $replacementPairs);
            unset($parts[$key]);
            $changed = true;
        }

        if ($changed) {
            uasort($parts, $this->getPartsComparer());
        }
    }

    /**
     * @phpstan-param array<string> $replacementPairs
     */
    protected function getNewKey(string $oldKey, array $replacementPairs): string
    {
        return strtr($this->getKeyTemplate(), $replacementPairs);
    }

    /**
     * @param array<string, mixed> $oldValue
     * @param array<string, string> $replacementPairs
     *
     * @return array<string, mixed>
     */
    protected function getNewValue(array $oldValue, array $replacementPairs): array
    {
        return $oldValue;
    }

    protected function isUnknown(string $key): bool
    {
        return preg_match('/^unknown\.\d+$/', $key) === 1;
    }

    /**
     * @param array<int|string, string> $matches
     *
     * @return array<string, string>
     */
    protected function convertMatchesToPairs(array $matches): array
    {
        $pairs = [];
        foreach ($matches as $name => $value) {
            if (!is_numeric($name)) {
                $pairs["{{ $name }}"] = $value;
            }
        }

        return $pairs;
    }

    protected function getPartsComparer(): callable
    {
        $comparer = new ArrayValueComparer();
        $comparer->setOptions([
            'keys' => [
                'weight' => [],
            ],
        ]);

        return $comparer;
    }
}
