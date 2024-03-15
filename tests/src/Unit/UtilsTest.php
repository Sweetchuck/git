<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Utils;
use PHPUnit\Framework\TestCase;

#[CoversClass(Utils::class)]
class UtilsTest extends TestCase
{

    /**
     * @return array<string, array{expected: string, diffFilter: array<string, ?bool>}>
     */
    public static function casesImplodeDiffFilter(): array
    {
        return [
            'empty' => [
                'expected' => '',
                'diffFilter' => [],
            ],
            'all null' => [
                'expected' => '',
                'diffFilter' => [
                    'A' => null,
                    'D' => null,
                    'M' => null,
                    'R' => null,
                    'T' => null,
                    'U' => null,
                    'X' => null,
                ],
            ],
            'all true' => [
                'expected' => 'ADMRTUX',
                'diffFilter' => [
                    'A' => true,
                    'D' => true,
                    'M' => true,
                    'R' => true,
                    'T' => true,
                    'U' => true,
                    'X' => true,
                ],
            ],
            'all false' => [
                'expected' => 'admrtux',
                'diffFilter' => [
                    'A' => false,
                    'D' => false,
                    'M' => false,
                    'R' => false,
                    'T' => false,
                    'U' => false,
                    'X' => false,
                ],
            ],
            'mixed 1' => [
                'expected' => 'AdMrTXc',
                'diffFilter' => [
                    'A' => true,
                    'D' => false,
                    'M' => true,
                    'R' => false,
                    'T' => true,
                    'U' => null,
                    'X' => true,
                    'B' => null,
                    'C' => false,
                ],
            ],
            'mixed 2 different order and case' => [
                'expected' => 'MAR',
                'diffFilter' => [
                    'm' => true,
                    'a' => true,
                    'r' => true,
                    'd' => null,
                ],
            ],
            'mixed 3' => [
                'expected' => 'Ab',
                'diffFilter' => [
                    'n' => null,
                    'a' => true,
                    'b' => false,
                ],
            ],
            'mixed 4' => [
                'expected' => 'aB',
                'diffFilter' => [
                    'n' => null,
                    'a' => true,
                    'A' => false,
                    'B' => false,
                    'b' => true,
                ],
            ],
        ];
    }

    /**
     * @param array<string, ?bool> $diffFilter
     */
    #[Test]
    #[DataProvider('casesImplodeDiffFilter')]
    public function testImplodeDiffFilter(string $expected, array $diffFilter): void
    {
        $utils = new Utils();
        static::assertSame($expected, $utils->implodeDiffFilter($diffFilter));
    }

    /**
     * @return array<string, array{expected: array<string, bool>, diffFilter: string}>
     */
    public static function casesExplodeDiffFilter(): array
    {
        return [
            'empty' => [
                'expected' => [],
                'diffFilter' => '',
            ],
            'all uppercase' => [
                'expected' => [
                    'A' => true,
                    'D' => true,
                    'M' => true,
                    'R' => true,
                    'T' => true,
                    'U' => true,
                    'X' => true,
                ],
                'diffFilter' => 'ADMRTUX',
            ],
            'all lowercase' => [
                'expected' => [
                    'A' => false,
                    'D' => false,
                    'M' => false,
                    'R' => false,
                    'T' => false,
                    'U' => false,
                    'X' => false,
                ],
                'diffFilter' => 'admrtux',
            ],
            'mixed case' => [
                'expected' => [
                    'A' => true,
                    'D' => false,
                    'M' => true,
                    'R' => false,
                    'T' => true,
                    'X' => true,
                    'C' => false,
                ],
                'diffFilter' => 'AdMrTXc',
            ],
            'different order' => [
                'expected' => [
                    'M' => true,
                    'A' => true,
                    'R' => true,
                ],
                'diffFilter' => 'MAR',
            ],
            'simple mixed' => [
                'expected' => [
                    'A' => true,
                    'B' => false,
                ],
                'diffFilter' => 'Ab',
            ],
            'duplicate letters' => [
                'expected' => [
                    'A' => true,
                    'B' => false,
                ],
                'diffFilter' => 'aABb',
            ],
        ];
    }

    /**
     * @param array<string, bool> $expected
     */
    #[Test]
    #[DataProvider('casesExplodeDiffFilter')]
    public function testExplodeDiffFilter(array $expected, string $diffFilter): void
    {
        $utils = new Utils();
        static::assertSame($expected, $utils->explodeDiffFilter($diffFilter));
    }
}
