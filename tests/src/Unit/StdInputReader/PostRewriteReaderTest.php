<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\StdInputReader;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\StdInputReader\BaseReader;
use Sweetchuck\Git\StdInputReader\Item\BaseItem;
use Sweetchuck\Git\StdInputReader\Item\PostRewriteItem;
use Sweetchuck\Git\StdInputReader\PostRewriteReader;

#[CoversClass(PostRewriteReader::class)]
#[CoversClass(BaseReader::class)]
#[CoversClass(PostRewriteItem::class)]
#[CoversClass(BaseItem::class)]
class PostRewriteReaderTest extends ReaderTestBase
{

    protected string $gitHook = 'post-rewrite';

    /**
     * @return array<string, mixed>
     */
    public static function caseAllInOne(): array
    {
        return [
            'basic' => [
                [
                    'a1 a2 a3',
                    'b1 b2',
                    'c1 c2 c3',
                    'd1 d2',
                    'e1 e2 e3',
                ],
            ],
        ];
    }
}
