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
        $artifacts['currentBranch'] = null;
        foreach ($artifacts[$options['assetKey']] ?? [] as $branch) {
            if (!empty($branch['isCurrentBranch'])) {
                $artifacts['currentBranch'] = $branch['refName'];

                break;
            }
        }

        return $artifacts;
    }
}
