<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\OutcomeParser\GrepFilesParser;

class GrepFilesParserTest extends TestBase
{
    protected function createParser(): GrepFilesParser
    {
        return new GrepFilesParser();
    }

    public static function casesParse(): array
    {
        return [
            'default-empty' => [
                'expected' => [
                    'files' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'default-single-basic' => [
                'expected' => [
                    'files' => [
                        'src/Command/CreateBranch.php' => [
                            'lines' => [
                                16 => 'class CreateBranch extends CliCommandBase',
                            ],
                            'matches' => [
                                16 => 1,
                            ],
                        ],
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    \PHP_EOL,
                    [
                        "src/Command/CreateBranch.php\x0016\x001\x00class CreateBranch extends CliCommandBase",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
            'default-single-context-all' => [
                'expected' => [
                    'files' => [
                        'src/Command/CreateBranch.php' => [
                            'lines' => [
                                13 => '/**',
                                14 => ' * My Doc.',
                                15 => ' */',
                                16 => 'class CreateBranch extends CliCommandBase',
                                17 => '',
                                18 => '    use MyTrait;',
                                19 => '',
                            ],
                            'matches' => [
                                16 => 1,
                            ],
                        ],
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    \PHP_EOL,
                    [
                        "src/Command/CreateBranch.php\x0013\x00/**",
                        "src/Command/CreateBranch.php\x0014\x00 * My Doc.",
                        "src/Command/CreateBranch.php\x0015\x00 */",
                        "src/Command/CreateBranch.php\x0016\x001\x00class CreateBranch extends CliCommandBase",
                        "src/Command/CreateBranch.php\x0017\x00",
                        "src/Command/CreateBranch.php\x0018\x00    use MyTrait;",
                        "src/Command/CreateBranch.php\x0019\x00",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
            'onlyFilePaths-empty' => [
                'expected' => [
                    'filePaths' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => ['stdOutputFormat' => 'onlyFilePaths'],
            ],
            'onlyFilePaths-single' => [
                'expected' => [
                    'filePaths' => ['a'],
                ],
                'exitCode' => 0,
                'stdOutput' => "a\0",
                'stdError' => '',
                'options' => ['stdOutputFormat' => 'onlyFilePaths'],
            ],
            'onlyFilePaths-multiple' => [
                'expected' => [
                    'filePaths' => ['a', 'b'],
                ],
                'exitCode' => 0,
                'stdOutput' => "a\0b",
                'stdError' => '',
                'options' => ['stdOutputFormat' => 'onlyFilePaths'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesHasContextLine(): array
    {
        return [
            'empty' => [
                'expected' => false,
                'text' => '',
            ],
            'begin-trailing-new-line-no' => [
                'expected' => true,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x00context line 01",
                        "x.php\x0042\x008\x00match line 01",
                    ],
                ),
            ],
            'begin-trailing-new-line-yes' => [
                'expected' => true,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x00context line 01",
                        "x.php\x0042\x008\x00match line 01",
                        '',
                    ],
                ),
            ],
            'middle' => [
                'expected' => true,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                        "x.php\x0042\x00context line 01",
                        "x.php\x0042\x008\x00match line02",
                    ],
                ),
            ],
            'end-trailing-new-line-no' => [
                'expected' => true,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                        "x.php\x0042\x00context line 01",
                    ],
                ),
            ],
            'end-trailing-new-line-yes' => [
                'expected' => true,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                        "x.php\x0042\x00context line 01",
                        '',
                    ],
                ),
            ],
            'match-single-trailing-new-line-no' => [
                'expected' => false,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                    ],
                ),
            ],
            'match-single-trailing-new-line-yes' => [
                'expected' => false,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                        '',
                    ],
                ),
            ],
            'match-multiple-trailing-new-line-no' => [
                'expected' => false,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                        "x.php\x0042\x008\x00match line 02",
                        "x.php\x0042\x008\x00match line 03",
                    ],
                ),
            ],
            'match-multiple-trailing-new-line-yes' => [
                'expected' => false,
                'text' => implode(
                    \PHP_EOL,
                    [
                        "x.php\x0042\x008\x00match line 01",
                        "x.php\x0042\x008\x00match line 02",
                        "x.php\x0042\x008\x00match line 03",
                        '',
                    ],
                ),
            ],
        ];
    }

    #[Test]
    #[DataProvider('casesHasContextLine')]
    public function testHasContextLine(bool $expected, string $text): void
    {
        $parser = $this->createParser();
        static::assertSame($expected, $parser->hasContextLine($text));
    }
}
