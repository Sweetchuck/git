<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\StdInputReader;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\StdInputReader\BaseReader;
use Sweetchuck\Git\StdInputReader\Item\BaseItem;
use Sweetchuck\Git\StdInputReader\Item\PrePushItem;
use Sweetchuck\Git\StdInputReader\PrePushReader;

#[CoversClass(PrePushReader::class)]
#[CoversClass(BaseReader::class)]
#[CoversClass(PrePushItem::class)]
#[CoversClass(BaseItem::class)]
class PrePushReaderTest extends ReaderTestBase
{

    protected string $gitHook = 'pre-push';

    /**
     * @return array<string, mixed>
     */
    public static function caseAllInOne(): array
    {
        return [
            'basic' => [
                [
                    'a1 a2 a3 a4',
                    'b1 b2 b3 b4',
                    'c1 c2 c3 c4',
                    'd1 d2 d3 d4',
                    'e1 e2 e3 e4',
                ],
            ],
        ];
    }
}
