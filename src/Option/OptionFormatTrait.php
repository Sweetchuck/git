<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\FormatHandler;
use Sweetchuck\Git\FormatHandlerInterface;

/**
 * @property array<string, mixed> $properties
 * @property \Sweetchuck\Git\Utils $utils
 */
trait OptionFormatTrait
{

    protected function initPropertyFormat(): static
    {
        $this->properties['commandOptions']['format'] = [
            'type' => 'value:string-required',
            'value' => null,
            'refPropertyMapping' => $this->getDefaultFormatRefPropertyMapping(),
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFormat(array $properties): static
    {
        if (array_key_exists('refPropertyMapping', $properties)) {
            $this->setFormatRefPropertyMapping($properties['refPropertyMapping']);
        }

        return $this;
    }

    protected ?FormatHandlerInterface $formatHandler = null;

    public function getFormatHandler(): FormatHandlerInterface
    {
        if (!$this->formatHandler) {
            // @todo Setter method or a service.
            $this->formatHandler = new FormatHandler();
        }

        return $this->formatHandler;
    }

    public function setFormatHandler(?FormatHandlerInterface $formatHandler): static
    {
        $this->formatHandler = $formatHandler;

        return $this;
    }

    /**
     * @return null|array<string, string>
     */
    public function getFormatRefPropertyMapping(): ?array
    {
        return $this->properties['commandOptions']['format']['refPropertyMapping'];
    }

    /**
     * @param null|array<string, string> $refPropertyMapping
     */
    public function setFormatRefPropertyMapping(?array $refPropertyMapping): static
    {
        $this->properties['commandOptions']['format']['refPropertyMapping'] = $refPropertyMapping;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    abstract protected function getMachineReadableFormatConfig(): array;

    protected function preGetCliCommandFormat(): static
    {
        $format = $this
            ->getFormatHandler()
            ->createMachineReadableFormatDefinition($this->getMachineReadableFormatConfig());

        $this->properties['commandOptions']['format']['value'] = $format['value'];
        $this->properties['commandOptions']['format']['definition'] = $format['definition'];

        return $this;
    }

    /**
     * @return array<string, string>
     */
    abstract protected function getDefaultFormatRefPropertyMapping(): ?array;
}
