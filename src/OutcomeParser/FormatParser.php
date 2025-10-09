<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\FileStatus;
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
    ): ?array {
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
        $asset = [];
        $rawRefsList = $this->splitRefs($definition, $stdOutput);
        foreach ($rawRefsList as $rawRef) {
            if (trim($rawRef) === '') {
                continue;
            }

            $refKeyValuePairs = [];
            $rawProperties = explode($definition['propertySeparator'], $rawRef);
            foreach ($rawProperties as $rawProperty) {
                [$propertyName, $propertyValue] = explode($definition['keyValueSeparator'], $rawProperty, 2);
                $refKeyValuePairs[$propertyName] = $propertyValue === ''
                    ? null
                    : $propertyValue;
            }

            $this->processRefProperties($refKeyValuePairs, $definition);

            ksort($refKeyValuePairs);

            $propertyName = (string) $refKeyValuePairs[$definition['keyProperty']];
            $asset[$propertyName] = $refKeyValuePairs;
        }

        return $asset;
    }

    /**
     * @param array<string, mixed> $definition
     * @param string $stdOutput
     *
     * @return array<string>
     */
    protected function splitRefs(array $definition, string $stdOutput): array
    {
        if (!trim($stdOutput)) {
            return [];
        }

        if ($definition['refSeparatorPosition'] === 'begin'
            && str_starts_with($stdOutput, $definition['refSeparator'])
        ) {
            $stdOutput = substr($stdOutput, strlen($definition['refSeparator']));
        }

        if ($definition['refSeparatorPosition'] === 'end'
            && str_ends_with($stdOutput, $definition['refSeparator'])
        ) {
            $stdOutput = substr($stdOutput, 0, strlen($definition['refSeparator']) * -1);
        }

        if (!str_ends_with($stdOutput, "\n")) {
            $stdOutput .= "\n";
        }

        return array_map(
            static fn (string $rawRef) => trim($rawRef, "\n\r"),
            explode($definition['refSeparator'], $stdOutput),
        );
    }

    /**
     * @param array<string, mixed> $refKeyValuePairs
     * @param array<string, mixed> $definition
     */
    protected function processRefProperties(array &$refKeyValuePairs, array $definition): static
    {
        foreach ($definition['refPropertyMapping'] as $propertyName => $placeholder) {
            switch ($placeholder) {
                case '%(refname)':
                case '%(refname:strip=0)':
                case '%(push)':
                case '%(push:strip=0)':
                case '%(upstream)':
                case '%(upstream:strip=0)':
                    $this->processRefPropertiesRefName($propertyName, $refKeyValuePairs);
                    break;

                case '%(HEAD)':
                    $this->processRefPropertiesHead($propertyName, $refKeyValuePairs);
                    break;

                case '%(upstream:track)':
                case '%(upstream:track,nobracket)':
                    $this->processRefPropertiesUpstreamTrack($propertyName, $refKeyValuePairs);
                    break;

                case '%(P)':
                    $this->processRefPropertiesCommitHashes($propertyName, $refKeyValuePairs);
                    break;

                case '%(objectsize)':
                    $this->processRefPropertiesObjectSize($propertyName, $refKeyValuePairs);
                    break;
            }
        }

        if (isset($refKeyValuePairs['nameStatus'])) {
            // @todo Do not use the human-readable property name as condition.
            $refKeyValuePairs['nameStatus'] = $this->parseNameStatus($refKeyValuePairs['nameStatus']);
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

    /**
     * @param array<string, mixed> $ref
     */
    protected function processRefPropertiesCommitHashes(string $key, array &$ref): void
    {
        $value = trim((string) $ref[$key]);
        $ref[$key] = $value === ''
            ? []
            : explode(' ', $value);
    }

    /**
     * @param array<string, mixed> $ref
     */
    protected function processRefPropertiesObjectSize(string $key, array &$ref): void
    {
        settype(
            $ref[$key],
            $ref[$key] === '-' ? 'null' : 'integer',
        );
    }

    /**
     * @phpstan-return array<string, array{filePath: string, status: \Sweetchuck\Git\FileStatus}>
     *
     * @todo Track file renames.
     */
    protected function parseNameStatus(string $text): array
    {
        $text = trim($text);
        if ($text === '') {
            return [];
        }

        $parts = explode("\x00", $text);
        $result = [];
        while ($parts) {
            $statusLetter = array_shift($parts);
            $filePath = array_shift($parts);
            if (str_starts_with($statusLetter, 'R')) {
                $result[$filePath] = [
                    'status' => FileStatus::Renamed,
                    'filePath' => array_shift($parts),
                    'oldFilePath' => $filePath,
                    'percent' => (int) mb_substr($statusLetter, 1),
                ];

                continue;
            }

            $result[$filePath] = [
                'status' => FileStatus::fromLogNameStatus($statusLetter),
                'filePath' => $filePath,
            ];
        }

        return $result;
    }
}
