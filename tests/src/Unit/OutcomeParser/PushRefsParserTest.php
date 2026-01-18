<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\OutcomeParser\PushRefsParser;
use Sweetchuck\Git\PushResult;

#[CoversClass(PushRefsParser::class)]
class PushRefsParserTest extends TestCase
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                'expected' => [],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
            ],
            'error' => [
                'expected' => null,
                'exitCode' => 1,
                'stdOutput' => '',
                'stdError' => 'error',
            ],
            'basic' => [
                'expected' => [
                    'refs/heads/1.x:refs/heads/1.x' => [
                        'result' => PushResult::UpToDate,
                        'refNameLocal' => 'refs/heads/1.x',
                        'refNameRemote' => 'refs/heads/1.x',
                        'message' => 'up to date'
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    "\n",
                    [
                        "=\trefs/heads/1.x:refs/heads/1.x\t[up to date]",
                    ],
                ),
                'stdError' => '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     */
    #[DataProvider('casesParse')]
    public function testParse(?array $expected, int $exitCode, string $stdOutput, string $stdError): void
    {
        $parser = new PushRefsParser();
        $this->assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError));
    }
}
