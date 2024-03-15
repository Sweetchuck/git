<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\ConfigValueHandlerInterface;
use Sweetchuck\Git\OutcomeParserInterface;

abstract class ConfigParserBase implements OutcomeParserInterface
{

    public function __construct(
        protected ConfigValueHandlerInterface $configValueHandler,
    ) {
    }

    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        if ($stdOutput === '') {
            return null;
        }

        $matches = [];
        preg_match_all(
            '/(?P<scope>[^\0]+)\0(?P<origin>[^\0]+)\0(?P<name>[^\n]+)\n(?P<value_raw>[^\0]+)\0/u',
            $stdOutput,
            $matches,
        );
        if (empty($matches[0])) {
            return null;
        }

        $config = [];
        foreach (array_keys($matches[0]) as $index) {
            $name = $matches['name'][$index];
            $entry = [
                'scope' => $matches['scope'][$index],
                'origin' => $matches['origin'][$index],
                'name' => $matches['name'][$index],
                'value.raw' => $matches['value_raw'][$index],
                'value' => $this->configValueHandler->transform($name, $matches['value_raw'][$index]),
            ];
            if (!empty($options['all'])) {
                $config[$name][] = $entry;
            } else {
                $config[$name] = $entry;
            }
        }

        return $config;
    }
}
