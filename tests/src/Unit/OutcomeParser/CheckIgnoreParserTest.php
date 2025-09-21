<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\OutcomeParser\CheckIgnoreParser;
use Sweetchuck\Git\OutcomeParserInterface;

#[CoversClass(CheckIgnoreParser::class)]
class CheckIgnoreParserTest extends TestBase
{
    protected function createParser(): OutcomeParserInterface
    {
        return new CheckIgnoreParser();
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
                'stdError' => '',
                'options' => [],
            ],
            'empty' => [
                'expected' => [
                    'matching' => [],
                    'nonMatching' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'empty-with-whitespace' => [
                'expected' => [
                    'matching' => [],
                    'nonMatching' => [],
                ],
                'exitCode' => 0,
                'stdOutput' => "\n\r\n",
                'stdError' => '',
                'options' => [],
            ],
            'basic exitCode-0' => [
                'expected' => [
                    'matching' => [
                        'a.xml' => [
                            [
                                'filePath' => '/home/me/.gitignore',
                                'lineNumber' => 1,
                                'pattern' => '*.xml',
                            ],
                            [
                                'filePath' => '.gitignore',
                                'lineNumber' => 1,
                                'pattern' => 'a*.xml',
                            ],
                        ],
                        'b.xml' => [
                            [
                                'filePath' => '/home/me/.gitignore',
                                'lineNumber' => 1,
                                'pattern' => '*.xml',
                            ],
                            [
                                'filePath' => '.gitignore',
                                'lineNumber' => 2,
                                'pattern' => 'b*.xml',
                            ],
                        ],
                    ],
                    'nonMatching' => [
                        '.non-matching-01.md',
                        '.non-matching-02.md',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => <<<TEXT
                    /home/me/.gitignore:1:*.xml\ta.xml
                    /home/me/.gitignore:1:*.xml\tb.xml
                    .gitignore:1:a*.xml\ta.xml
                    .gitignore:2:b*.xml\tb.xml
                    ::\t.non-matching-01.md
                    ::\t.non-matching-02.md
                    TEXT,
                'stdError' => '',
                'options' => [],
            ],
            'basic exitCode-1 with trailing newline' => [
                'expected' => [
                    'matching' => [
                        'a.xml' => [
                            [
                                'filePath' => '/home/me/.gitignore',
                                'lineNumber' => 1,
                                'pattern' => '*.xml',
                            ],
                            [
                                'filePath' => '.gitignore',
                                'lineNumber' => 1,
                                'pattern' => 'a*.xml',
                            ],
                        ],
                        'b.xml' => [
                            [
                                'filePath' => '/home/me/.gitignore',
                                'lineNumber' => 1,
                                'pattern' => '*.xml',
                            ],
                            [
                                'filePath' => '.gitignore',
                                'lineNumber' => 2,
                                'pattern' => 'b*.xml',
                            ],
                        ],
                    ],
                    'nonMatching' => [
                        '.non-matching-01.md',
                        '.non-matching-02.md',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => <<<TEXT
                    /home/me/.gitignore:1:*.xml\ta.xml
                    /home/me/.gitignore:1:*.xml\tb.xml
                    .gitignore:1:a*.xml\ta.xml
                    .gitignore:2:b*.xml\tb.xml
                    ::\t.non-matching-01.md
                    ::\t.non-matching-02.md

                    TEXT,
                'stdError' => '',
                'options' => [],
            ],
        ];
    }
}
