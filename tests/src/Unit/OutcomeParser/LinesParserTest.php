<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\OutcomeParser\LinesParser;
use Sweetchuck\Git\OutcomeParserInterface;

#[CoversClass(LinesParser::class)]
class LinesParserTest extends TestBase
{
    protected function createParser(): OutcomeParserInterface
    {
        return new LinesParser();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                'expected' => [
                    'stdOutput.lines' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'empty-with-whitespace' => [
                'expected' => [
                    'stdOutput.lines' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => "\n\r\n",
                'stdError' => '',
                'options' => [],
            ],
            'single-line' => [
                'expected' => [
                    'stdOutput.lines' => ['hello world'],
                ],
                'exitCode' => 0,
                'stdOutput' => 'hello world',
                'stdError' => '',
                'options' => [],
            ],
            'single-line-with-trailing-newline' => [
                'expected' => [
                    'stdOutput.lines' => ['hello world'],
                ],
                'exitCode' => 0,
                'stdOutput' => "hello world\n",
                'stdError' => '',
                'options' => [],
            ],
            'two-lines' => [
                'expected' => [
                    'stdOutput.lines' => ['line1', 'line2'],
                ],
                'exitCode' => 0,
                'stdOutput' => "line1\nline2",
                'stdError' => '',
                'options' => [],
            ],
            'two-lines-with-carriage-return' => [
                'expected' => [
                    'stdOutput.lines' => ['line1', 'line2'],
                ],
                'exitCode' => 0,
                'stdOutput' => "line1\r\nline2",
                'stdError' => '',
                'options' => [],
            ],
            'multiple-lines' => [
                'expected' => [
                    'stdOutput.lines' => ['first', 'second', 'third', 'fourth'],
                ],
                'exitCode' => 0,
                'stdOutput' => "first\nsecond\nthird\nfourth",
                'stdError' => '',
                'options' => [],
            ],
            'multiple-lines-with-mixed-endings' => [
                'expected' => [
                    'stdOutput.lines' => ['first', 'second', 'third', 'fourth'],
                ],
                'exitCode' => 0,
                'stdOutput' => "first\r\nsecond\nthird\r\nfourth",
                'stdError' => '',
                'options' => [],
            ],
            'multiple-lines-with-empty-lines' => [
                'expected' => [
                    'stdOutput.lines' => ['first', 'third', 'fifth'],
                ],
                'exitCode' => 0,
                'stdOutput' => "first\n\nthird\n\nfifth",
                'stdError' => '',
                'options' => [],
            ],
            'custom-key' => [
                'expected' => [
                    'custom.lines' => ['line1', 'line2'],
                ],
                'exitCode' => 0,
                'stdOutput' => "line1\nline2",
                'stdError' => '',
                'options' => [
                    'key' => 'custom.lines',
                ],
            ],
            'trailing-whitespace' => [
                'expected' => [
                    'stdOutput.lines' => ['line1', 'line2'],
                ],
                'exitCode' => 0,
                'stdOutput' => "\n\rline1\nline2\r\n\r\n",
                'stdError' => '',
                'options' => [],
            ],
        ];
    }
}
