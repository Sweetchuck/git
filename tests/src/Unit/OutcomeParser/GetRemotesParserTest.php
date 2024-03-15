<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\OutcomeParser\GetRemotesParser;

class GetRemotesParserTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParseSuccessful(): array
    {
        return [
            'empty' => [
                'expected' => [],
                'args' => [
                    0,
                    '',
                    '',
                ],
            ],
            'basic' => [
                'expected' => [
                    'a' => [
                        'fetch' => 'b@c.d/e.git',
                        'push' => 'b@c.d/e.git',
                    ],
                    'fg' => [
                        'fetch' => 'h@i.j/k.git',
                        'push' => 'h@i.j/k.git',
                    ],
                ],
                'args' => [
                    0,
                    implode(PHP_EOL, [
                        'a  b@c.d/e.git (fetch)',
                        'a  b@c.d/e.git (push)',
                        'fg h@i.j/k.git (fetch)',
                        'fg h@i.j/k.git (push)',
                        '',
                    ]),
                    '',
                ],
            ],
            'exitCode non-zero' => [
                'expected' => null,
                'args' => [
                    42,
                    '',
                    '',
                ],
            ],
        ];
    }

    /**
     * @param null|array<string, mixed> $expected
     * @param array<string, mixed> $args
     */
    #[Test]
    #[DataProvider('casesParseSuccessful')]
    public function testParseSuccessful(?array $expected, array $args): void
    {
        $parser = new GetRemotesParser();
        static::assertSame($expected, $parser->parse(...$args));
    }
}
