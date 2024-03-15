<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader\Item;

class ReceiveItem extends BaseItem
{
    public string $oldValue;

    public string $newValue;

    public string $refName;

    public function __construct(string $oldValue, string $newValue, string $refName)
    {
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
        $this->refName = $refName;
    }

    /**
     * @return string[]
     */
    protected function getPropertyValues(): array
    {
        return [
            $this->oldValue,
            $this->newValue,
            $this->refName,
        ];
    }
}
