<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

class GetConfigSingleParser extends ConfigParserBase
{

    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        $result = parent::parse($exitCode, $stdOutput, $stdError, $options);
        if ($result === null) {
            return null;
        }

        if (!empty($options['all'])) {
            return $result;
        }

        return reset($result) ?: null;
    }
}
