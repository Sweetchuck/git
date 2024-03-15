<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\OutcomeParser\GetStatusParser;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(GetStatusParser::class)]
class StatusParserTest extends TestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                [],
                0,
                '',
                '',
            ],
            'basic' => [
                [
                    'a.txt' => ' D',
                    'b.txt' => 'MM',
                    'c.txt' => 'D ',
                ],
                0,
                implode("\0", [
                    ' D a.txt',
                    'MM b.txt',
                    'D  c.txt',
                ]),
                '',
            ],
        ];
    }

    /**
     * @param array<mixed> $expected
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(array $expected, int $exitCode, string $stdOutput, string $stdError = ''): void
    {
        $parser = new GetStatusParser();
        static::assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError));
    }
}
