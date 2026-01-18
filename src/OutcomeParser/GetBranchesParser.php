<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

class GetBranchesParser extends FormatParser
{

    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        $options += [
            'assetKey' => 'branches',
        ];
        $artifacts = parent::parse($exitCode, $stdOutput, $stdError, $options);
        $this
            ->parseDetached($options, $artifacts)
            ->parseCurrentBranch($options, $artifacts);

        return $artifacts;
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $artifacts
     */
    protected function parseDetached(array $options, array &$artifacts): static
    {
        $topKey = $options['assetKey'];
        foreach ($artifacts[$topKey] as $branchId => $branch) {
            $commitId = $this->parseDetachedBranchName($branch['refName']);
            if (!$commitId) {
                // @phpstan-ignore-next-line
                $artifacts[$topKey][$branchId]['isDetached'] = false;

                continue;
            }

            // @phpstan-ignore-next-line
            unset($artifacts[$topKey][$branchId]);
            $branch['refName'] = $commitId;
            $branch['refName.short'] = $commitId;
            $branch['isDetached'] = true;
            // @phpstan-ignore-next-line
            $artifacts[$topKey][$commitId] = $branch;
        }

        return $this;
    }

    protected function parseDetachedBranchName(string $refName): ?string
    {
        $pattern = '/^\(HEAD detached at (?P<commitId>[0-9a-f]{7,40})\)$/';
        $matches = [];
        preg_match($pattern, $refName, $matches);

        return $matches['commitId'] ?? null;
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $artifacts
     */
    protected function parseCurrentBranch(array $options, array &$artifacts): static
    {
        $topKey = $options['assetKey'];
        $artifacts['currentBranch'] = null;
        foreach ($artifacts[$topKey] ?? [] as $branch) {
            if (!empty($branch['isCurrentBranch'])) {
                $artifacts['currentBranch'] = $branch['refName'];

                break;
            }
        }

        return $this;
    }
}
