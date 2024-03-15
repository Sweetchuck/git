<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\OutcomeParser\LastLineParser;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(LastLineParser::class)]
class LastLineParserTest extends TestBase
{
    protected function createParser(): LastLineParser
    {
        return new LastLineParser();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                'expected' => null,
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                    'options' => [],
                ],
            ],
            'single-line' => [
                'expected' => [
                    'stdOutput.lastLine' => 'foo',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo",
                    'stdError' => '',
                    'options' => [],
                ],
            ],
            'multiple-lines' => [
                'expected' => [
                    'execPath' => 'bar',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo\nbar",
                    'stdError' => '',
                    'options' => [
                        'key' => 'execPath',
                    ],
                ],
            ],
            'multiple-lines-with-empty-lines' => [
                'expected' => [
                    'execPath' => 'baz',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo\n\nbar\n\nbaz",
                    'stdError' => '',
                    'options' => [
                        'key' => 'execPath',
                    ],
                ],
            ],
            'multiple-lines-with-windows-line-endings' => [
                'expected' => [
                    'execPath' => 'qux',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo\r\nbar\r\nbaz\r\nqux",
                    'stdError' => '',
                    'options' => [
                        'key' => 'execPath',
                    ],
                ],
            ],
            'multiple-lines-with-mixed-line-endings' => [
                'expected' => [
                    'execPath' => 'quux',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo\nbar\r\nbaz\nquux",
                    'stdError' => '',
                    'options' => [
                        'key' => 'execPath',
                    ],
                ],
            ],
            'trailing-newlines' => [
                'expected' => [
                    'execPath' => 'bar',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo\nbar\n\n",
                    'stdError' => '',
                    'options' => [
                        'key' => 'execPath',
                    ],
                ],
            ],
            'trailing-carriage-returns' => [
                'expected' => [
                    'execPath' => 'bar',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "foo\nbar\r\n\r\n",
                    'stdError' => '',
                    'options' => [
                        'key' => 'execPath',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param null|array<string, mixed> $expected
     * @param array<string, mixed> $args
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(?array $expected, array $args): void
    {
        $parser = $this->createParser();
        static::assertSame($expected, $parser->parse(...$args));
    }
}
