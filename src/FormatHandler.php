<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

class FormatHandler implements FormatHandlerInterface
{
    protected bool $moreEntropy = false;

    /**
     * @var null|callable
     */
    protected $uniqueIdGenerator = null;

    public function getUniqueIdGenerator(): ?callable
    {
        return $this->uniqueIdGenerator;
    }

    public function getFinalUniqueIdGenerator(): ?callable
    {
        return $this->getUniqueIdGenerator() ?: $this->getDefaultUniqueIdGenerator();
    }

    protected function getDefaultUniqueIdGenerator(): callable
    {
        return function (): string {
            $this->moreEntropy = !$this->moreEntropy;

            return uniqid(more_entropy: $this->moreEntropy);
        };
    }

    public function setUniqueIdGenerator(?callable $uniqueIdGenerator): static
    {
        $this->uniqueIdGenerator = $uniqueIdGenerator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createMachineReadableFormatDefinition(array $properties): array
    {
        $uniqueIdGenerator = $this->getFinalUniqueIdGenerator();

        $properties += [
            'refName' => 'refname:strip=0'
        ];

        $definition = [
            'key' => 'refName',
            'refSeparator' => $uniqueIdGenerator(),
            'propertySeparator' => $uniqueIdGenerator(),
            'keyValueSeparator' => ' ',
            'properties' => $properties,
        ];

        $format = [];
        foreach ($properties as $key => $pattern) {
            $format[$key] = "{$key}{$definition['keyValueSeparator']}%($pattern)";
        }

        $definition['format'] = implode($definition['propertySeparator'], $format);
        $definition['format'] .= $definition['refSeparator'];

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    public function parseStdOutput(string $stdOutput, array $definition): array
    {
        $asset = [];
        $refs = explode("{$definition['refSeparator']}\n", $stdOutput);
        array_pop($refs);
        foreach ($refs as $refProperties) {
            $ref = [];
            $refProperties = explode($definition['propertySeparator'], $refProperties);
            foreach ($refProperties as $property) {
                [$key, $value] = explode($definition['keyValueSeparator'], $property, 2);
                $ref[$key] = $value;
            }

            $this->processRefProperties($ref, $definition);

            ksort($ref);

            $key = $ref[$definition['key']];
            $asset[$key] = $ref;
        }

        return $asset;
    }

    /**
     * @param array<string, string> $ref
     * @param array<string, mixed> $definition
     */
    protected function processRefProperties(array &$ref, array $definition): static
    {
        foreach ($definition['properties'] as $propertyName => $fieldName) {
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
     * @param array<string, string> $ref
     */
    protected function processRefPropertiesRefName(string $key, array &$ref): void
    {
        $ref += [
            "$key.short" => preg_replace(
                '@^refs/(heads|tags|remotes)/@',
                '',
                $ref[$key],
            ),
        ];
    }

    /**
     * @param array<string, string> $ref
     */
    protected function processRefPropertiesHead(string $key, array &$ref): void
    {
        $ref[$key] = (bool) trim($ref[$key]);
    }

    /**
     * @param array<string, string> $ref
     */
    protected function processRefPropertiesUpstreamTrack(string $key, array &$ref): void
    {
        $value = trim($ref[$key], '[]');

        $additions = [
            "$key.gone" => $value === 'gone',
        ];

        // @todo Support for sync.
        foreach (['ahead', 'behind'] as $keySuffix) {
            $matches = [];
            preg_match("/$keySuffix (?P<numOfCommits>\d+)/", $value, $matches);
            $additions["$key.$keySuffix"] = $matches ? (int) $matches['numOfCommits'] : null;
        }

        $ref += $additions;
    }
}
