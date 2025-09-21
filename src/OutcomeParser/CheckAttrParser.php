<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class CheckAttrParser implements OutcomeParserInterface
{

    /**
     * {@inheritdoc}
    */
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        if ($exitCode !== 0) {
            return null;
        }

        $pattern = '/^(?P<filePath>[^\x00]+)\x00(?P<attribute>[^\x00]+)\x00(?P<value>[^\x00]*)(\x00|$)/u';
        $artifacts = [
            'filePaths' => [],
        ];
        $matches = [];
        while (preg_match($pattern, $stdOutput, $matches) === 1) {
            $artifacts['filePaths'][$matches['filePath']][$matches['attribute']] = $matches['value'];
            $stdOutput = mb_substr($stdOutput, mb_strlen($matches[0]));
        }

        // @todo Attribute specific parsers (eol, whitespace, etc.).
        return $artifacts;
    }
}
