<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPointsAtTrait
{

    protected function initPropertyPointsAt(): static
    {
        $this->properties['commandOptions']['pointsAt'] = [
            'type' => 'value:true-false:string',
            'name' => '--points-at',
            'name-no' => '--no-points-at',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPointsAt(array $properties): static
    {
        if (array_key_exists('pointsAt', $properties)) {
            $this->setPointsAt($properties['pointsAt']);
        }

        return $this;
    }

    public function getPointsAt(): null|false|string
    {
        return $this->properties['commandOptions']['pointsAt']['value'];
    }

    public function setPointsAt(null|false|string $value): static
    {
        $this->properties['commandOptions']['pointsAt']['value'] = $value;

        return $this;
    }
}
