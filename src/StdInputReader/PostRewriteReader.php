<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader;

use Sweetchuck\Git\StdInputReader\Item\BaseItem;

/**
 * @template TKey int
 * @template TValue \Sweetchuck\Git\StdInputReader\Item\PostRewriteItem
 * @extends \Sweetchuck\Git\StdInputReader\BaseReader<TKey, TValue>
 */
class PostRewriteReader extends BaseReader
{

    /**
     * @return \Sweetchuck\Git\StdInputReader\Item\PostRewriteItem
     */
    protected function parse(string $line): BaseItem
    {
        // @todo Validate.
        return new Item\PostRewriteItem(...explode(' ', trim($line)));
    }
}
