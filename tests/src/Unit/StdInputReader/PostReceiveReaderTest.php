<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\StdInputReader;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\StdInputReader\BaseReader;
use Sweetchuck\Git\StdInputReader\Item\BaseItem;
use Sweetchuck\Git\StdInputReader\Item\ReceiveItem;
use Sweetchuck\Git\StdInputReader\PostReceiveReader;

#[CoversClass(PostReceiveReader::class)]
#[CoversClass(BaseReader::class)]
#[CoversClass(ReceiveItem::class)]
#[CoversClass(BaseItem::class)]
class PostReceiveReaderTest extends ReaderTestBase
{

    protected string $gitHook = 'post-receive';

    /**
     * @return array<string, mixed>
     */
    public static function caseAllInOne(): array
    {
        return [
            'basic' => [
                'lines' => [
                    'a1 a2 a3',
                    'b1 b2 b3',
                    'c1 c2 c3',
                    'd1 d2 d3',
                    'e1 e2 e3',
                ],
            ],
        ];
    }
}
