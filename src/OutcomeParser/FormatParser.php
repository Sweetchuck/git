<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class FormatParser implements OutcomeParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): array {
        return [
            $options['assetKey'] => $this->parseStdOutput($stdOutput, $options['definition']),
        ];
    }

    /**
     * @param array<string, mixed> $definition
     *
     * @return array<string, mixed>
     */
    public function parseStdOutput(string $stdOutput, array $definition): array
    {
        if (!trim($stdOutput)) {
            return [];
        }

        if (!str_ends_with($stdOutput, "\n")) {
            $stdOutput .= "\n";
        }

        $asset = [];
        $refs = explode("{$definition['refSeparator']}\n", $stdOutput);
        foreach ($refs as $refProperties) {
            if ($refProperties === '') {
                continue;
            }

            $ref = [];
            $refProperties = explode($definition['propertySeparator'], $refProperties);
            foreach ($refProperties as $property) {
                [$key, $value] = explode($definition['keyValueSeparator'], $property, 2);
                $ref[$key] = $value === ''
                    ? null
                    : $value;
            }

            $this->processRefProperties($ref, $definition);

            ksort($ref);

            $key = (string) $ref[$definition['key']];
            $asset[$key] = $ref;
        }

        return $asset;
    }

    /**
     * @param array<string, mixed> $ref
     * @param array<string, mixed> $definition
     */
    protected function processRefProperties(array &$ref, array $definition): static
    {
        foreach ($definition['refPropertyMapping'] as $propertyName => $fieldName) {
            switch ($fieldName) {
                case 'refname':
                case 'refname:strip=0':
                case 'push':
                case 'push:strip=0':
                case 'upstream':
                case 'upstream:strip=0':
                    $this->processRefPropertiesRefName($propertyName, $ref);
                    break;

                case 'HEAD':
                    $this->processRefPropertiesHead($propertyName, $ref);
                    break;

                case 'upstream:track':
                case 'upstream:track,nobracket':
                    $this->processRefPropertiesUpstreamTrack($propertyName, $ref);
                    break;
            }
        }

        return $this;
    }

    /**
     * @param array<string, ?string> $ref
     */
    protected function processRefPropertiesRefName(string $key, array &$ref): void
    {
        if ($ref[$key] === null) {
            $ref += [
                "$key.short" => null,
            ];

            return;
        }

        $ref += [
            "$key.short" => preg_replace(
                '@^refs/(heads|tags|remotes)/@',
                '',
                $ref[$key],
            ),
        ];
    }

    /**
     * @param array<string, null|bool|string> $ref
     */
    protected function processRefPropertiesHead(string $key, array &$ref): void
    {
        $ref[$key] = (bool) trim((string) $ref[$key]);
    }

    /**
     * @param array<string, mixed> $ref
     */
    protected function processRefPropertiesUpstreamTrack(string $key, array &$ref): void
    {
        $value = trim((string) $ref[$key], '[]');

        $additions = [
            "$key.gone" => $value === 'gone',
        ];

        // @todo Support for sync.
        foreach (['ahead', 'behind'] as $keySuffix) {
            $matches = [];
            preg_match("/$keySuffix (?P<numOfCommits>\d+)/", $value, $matches);
            $additions["$key.$keySuffix"] = $matches
                ? (int) $matches['numOfCommits']
                : null;
        }

        $ref += $additions;
    }
}
