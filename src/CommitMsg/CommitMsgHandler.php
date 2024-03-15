<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\CommitMsg;

use Sweetchuck\Utils\Comparer\ArrayValueComparer;
use Sweetchuck\Utils\Filter\EnabledFilter;

/**
 * @phpstan-import-type SweetchuckGitCommitMsgPart from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitCommitMsgPartModifier from \Sweetchuck\Git\Phpstan
 */
class CommitMsgHandler
{

    /**
     * @var string[]
     */
    protected array $lines = [];

    protected int $lineIndex = 0;

    /**
     * @var array<string, SweetchuckGitCommitMsgPart>
     */
    protected array $parts = [];

    /**
     * @phpstan-param array<string, SweetchuckGitCommitMsgPartModifier> $modifiers
     *
     * @phpstan-return array<string, SweetchuckGitCommitMsgPart>
     */
    public function parse(
        string $commitMsg,
        array $modifiers = [],
    ): array {
        return $this
            ->parseInit($commitMsg)
            ->parseSubject()
            ->parseFooterComment()
            ->parseBody()
            ->parseInvokeModifiers($modifiers)
            ->parseResult();
    }

    /**
     * @param array<string, SweetchuckGitCommitMsgPart> $parts
     */
    public function render(array $parts): string
    {
        $parts = array_filter(
            $parts,
            new EnabledFilter(),
        );
        $comparer = new ArrayValueComparer();
        $comparer->setKeys([
            'weight' => [],
        ]);
        usort($parts, $comparer);

        $result = '';
        foreach ($parts as $part) {
            $result .= str_repeat("\n", $part['marginTop']);
            $result .= $part['content'];
        }

        return $result;
    }

    protected function parseInit(string $commitMsg): static
    {
        $this->lineIndex = 0;
        $this->lines = explode("\n", trim($commitMsg));
        $this->parts = [];

        return $this;
    }

    protected function parseSubject(): static
    {
        if ($this->isContent($this->lineIndex)
            && $this->isEmpty($this->lineIndex + 1)
        ) {
            $this->parts['subject'] = [
                'enabled' => true,
                'weight' => -999,
                'marginTop' => 0,
                'content' => $this->lines[$this->lineIndex] . "\n",
            ];

            $this->lineIndex++;
        }

        return $this;
    }

    protected function parseFooterComment(): static
    {
        $lastIndex = count($this->lines) - 1;
        $contentStart = $lastIndex;
        while ($contentStart >= $this->lineIndex
            && $this->isComment($contentStart - 1)
        ) {
            $contentStart--;
        }

        $marginTopStart = $contentStart;
        while ($marginTopStart >= $this->lineIndex
            && $this->isEmpty($marginTopStart - 1)
        ) {
            $marginTopStart--;
        }

        if ($lastIndex === $marginTopStart) {
            return $this;
        }

        $this->parts['footer_comment'] = [
            'enabled' => true,
            'weight' => 999,
            'marginTop' => $contentStart - $marginTopStart,
            'content' => implode(
                "\n",
                array_splice($this->lines, $contentStart),
            ) . "\n",
        ];

        // Remove the footer_comment related lines from the original content,
        // ::parseBody won't parse it again.
        array_splice($this->lines, $marginTopStart);

        return $this;
    }

    protected function parseBody(): static
    {
        while ($this->lineIndex < count($this->lines)) {
            $marginTopStart = $this->lineIndex;
            while ($this->isExists($this->lineIndex)
                && $this->isEmpty($this->lineIndex)
            ) {
                $this->lineIndex++;
            }
            $marginTopEnd = $this->lineIndex;

            while ($this->isExists($this->lineIndex)
                && !$this->isEmpty($this->lineIndex)
            ) {
                $this->lineIndex++;
            }

            $weight = count($this->parts);
            $this->parts["unknown.$weight"] = [
                'enabled' => true,
                'weight' => $weight,
                'marginTop' => $marginTopEnd - $marginTopStart,
                'content' => implode(
                    "\n",
                    array_slice(
                        $this->lines,
                        $marginTopEnd,
                        $this->lineIndex - $marginTopEnd,
                    ),
                ) . "\n",
            ];
        }

        return $this;
    }

    /**
     * @phpstan-param array<string, SweetchuckGitCommitMsgPartModifier> $modifiers
     */
    protected function parseInvokeModifiers(array $modifiers): static
    {
        $modifiers = array_filter(
            $modifiers,
            new EnabledFilter(),
        );

        $comparer = new ArrayValueComparer();
        $comparer->setOptions([
            'keys' => [
                'weight' => [],
            ],
        ]);
        uasort($modifiers, $comparer);

        foreach ($modifiers as $modifier) {
            $modifier['callback']($this->parts);
        }

        return $this;
    }

    /**
     *
     * @return array<string, SweetchuckGitCommitMsgPart>
     */
    protected function parseResult(): array
    {
        $parts = $this->parts;
        $this->lineIndex = 0;
        $this->lines = [];
        $this->parts = [];

        $comparer = new ArrayValueComparer();
        $comparer->setKeys([
            'weight' => [],
        ]);

        uasort($parts, $comparer);

        return $parts;
    }

    protected function isExists(int $index): bool
    {
        return isset($this->lines[$index]);
    }

    protected function isComment(int $index): bool
    {
        return isset($this->lines[$index])
            && str_starts_with($this->lines[$index], '#');
    }

    protected function isEmpty(int $index): bool
    {
        return !isset($this->lines[$index])
            || $this->lines[$index] === '';
    }

    protected function isContent(int $index): bool
    {
        return $this->isExists($index)
            && !$this->isComment($index)
            && !$this->isEmpty($index);
    }
}
