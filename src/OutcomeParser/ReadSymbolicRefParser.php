<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class ReadSymbolicRefParser implements OutcomeParserInterface
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

        $nameFull = trim($stdOutput);
        if ($nameFull === '') {
            return null;
        }

        $artifacts = [
            'name.full' => $nameFull,
            'name.short' => null,
            'type' => null,
        ];

        $parts = explode('/', $nameFull);
        $topLevel = array_shift($parts);
        if ($topLevel === 'refs') {
            $artifacts['type'] = array_shift($parts);
            $artifacts['name.short'] = implode('/', $parts);
        }

        return $artifacts;
    }
}
