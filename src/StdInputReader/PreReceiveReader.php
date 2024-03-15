<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader;

/**
 * @template TKey int
 * @template TValue \Sweetchuck\Git\StdInputReader\Item\ReceiveItem
 * @extends \Sweetchuck\Git\StdInputReader\PostReceiveReader<TKey, TValue>
 */
class PreReceiveReader extends PostReceiveReader
{
}
