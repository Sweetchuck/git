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

    public function setUniqueIdGenerator(?callable $uniqueIdGenerator): static
    {
        $this->uniqueIdGenerator = $uniqueIdGenerator;

        return $this;
    }

    public function getFinalUniqueIdGenerator(): callable
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

    /**
     * {@inheritdoc}
     */
    public function createMachineReadableFormatDefinition(?array $refPropertyMapping): array
    {
        if ($refPropertyMapping === null) {
            return [
                'value' => null,
                'definition' => null,
            ];
        }

        $uniqueIdGenerator = $this->getFinalUniqueIdGenerator();

        $refPropertyMapping += [
            'refName' => 'refname:strip=0'
        ];

        $definition = [
            'key' => 'refName',
            'refSeparator' => $uniqueIdGenerator(),
            'propertySeparator' => $uniqueIdGenerator(),
            'keyValueSeparator' => ' ',
            'refPropertyMapping' => $refPropertyMapping,
        ];

        $format = [];
        foreach ($refPropertyMapping as $key => $pattern) {
            $format[$key] = "{$key}{$definition['keyValueSeparator']}%($pattern)";
        }

        $value = implode($definition['propertySeparator'], $format);
        $value .= $definition['refSeparator'];

        return [
            'value' => $value,
            'definition' => $definition,
        ];
    }
}
