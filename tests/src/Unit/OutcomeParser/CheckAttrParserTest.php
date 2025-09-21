<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\OutcomeParser\CheckAttrParser;
use Sweetchuck\Git\OutcomeParserInterface;

#[CoversClass(CheckAttrParser::class)]
class CheckAttrParserTest extends TestBase
{
    protected function createParser(): OutcomeParserInterface
    {
        return new CheckAttrParser();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesParse(): array
    {
        return [
            'exitCode-error' => [
                'expected' => null,
                'exitCode' => 2,
                'stdOutput' => '',
            ],
            'empty' => [
                'expected' => [
                    'filePaths' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => '',
            ],
            'empty-with-whitespace' => [
                'expected' => [
                    'filePaths' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => "\n\r\n",
            ],
            'basic exitCode-0' => [
                'expected' => [
                    'filePaths' => [
                        'a.php' => [
                            'eol' => 'lf',
                            'diff' => 'php',
                        ],
                        'b.php' => [
                            'eol' => 'lf',
                            'whitespace' => 'blank-at-eol,-blank-at-eof,-space-before-tab,tab-in-indent,tabwidth=4',
                        ],
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    "\x00",
                    [
                        'a.php', 'eol', 'lf',
                        'a.php', 'diff', 'php',
                        'b.php', 'eol', 'lf',
                        'b.php', 'whitespace', 'blank-at-eol,-blank-at-eof,-space-before-tab,tab-in-indent,tabwidth=4',
                        '',
                    ],
                ),
            ],
        ];
    }
}
