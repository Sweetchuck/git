<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader;

use Sweetchuck\Git\StdInputReader\Item\BaseItem;
use Sweetchuck\Git\StdInputReader\Item\ReceiveItem;

/**
 * @template TKey int
 * @template TValue \Sweetchuck\Git\StdInputReader\Item\ReceiveItem
 * @extends \Sweetchuck\Git\StdInputReader\BaseReader<TKey, TValue>
 */
class PostReceiveReader extends BaseReader
{

    /**
     * @return \Sweetchuck\Git\StdInputReader\Item\ReceiveItem
     */
    protected function parse(string $line): BaseItem
    {
        // @todo Validate.
        return new ReceiveItem(...explode(' ', trim($line)));
    }
}
