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
    public function createMachineReadableFormatDefinition(array $config): array
    {
        if (empty($config['refPropertyMapping'])) {
            return [
                'value' => null,
                'definition' => null,
            ];
        }

        $uniqueIdGenerator = $this->getFinalUniqueIdGenerator();

        $config += [
            'keyProperty' => 'refName',
            'refSeparatorPosition' => 'begin',
            'keyValueSeparator' => '=',
        ];

        if (!array_key_exists('refSeparator', $config)) {
            $config['refSeparator'] = $uniqueIdGenerator();
        }

        if (!array_key_exists('propertySeparator', $config)) {
            $config['propertySeparator'] = $uniqueIdGenerator();
        }

        $format = [];
        foreach ($config['refPropertyMapping'] as $key => $pattern) {
            $format[] = "$key{$config['keyValueSeparator']}$pattern";
        }

        $prefix = $config['refSeparatorPosition'] === 'begin'
            ? $config['refSeparator']
            : '';
        $suffix = $config['refSeparatorPosition'] === 'end'
            ? $config['refSeparator']
            : '';
        $value = $prefix . implode($config['propertySeparator'], $format) .  $suffix;

        return [
            'value' => $value,
            'definition' => $config,
        ];
    }
}
