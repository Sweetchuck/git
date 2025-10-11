<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParser\FormatParser;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormatParser::class)]
class FormatParserTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParseSuccessful(): array
    {
        $branchOptions = [
            'definition' => [
                'keyProperty' => 'refName',
                'refSeparatorPosition' => 'begin',
                'refSeparator' => '¤',
                'propertySeparator' => '×',
                'keyValueSeparator' => '=',
                'refPropertyMapping' => [
                    'refName' => '%(refname:strip=0)',
                    'upstream' => '%(upstream:strip=0)',
                    'track' => '%(upstream:track)',
                    'push' => '%(push:strip=0)',
                    'isCurrentBranch' => '%(HEAD)',
                ],
            ],
            'assetKey' => 'branches',
        ];

        $logOptions = [
            'definition' => [
                'keyProperty' => 'commitHash',
                'refSeparatorPosition' => 'begin',
                'refSeparator' => "¤",
                'propertySeparator' => '×',
                'keyValueSeparator' => '=',
                'refPropertyMapping' => [
                    'commitHash' => '%H',
                    'commitMessage.subject' => '%s',
                    'nameStatus' => '',
                ],
            ],
            'assetKey' => 'commits',
        ];

        $lsTreeOptions = [
            'definition' => [
                'keyProperty' => 'path',
                'refSeparatorPosition' => 'after',
                'refSeparator' => "\0",
                'propertySeparator' => '|',
                'keyValueSeparator' => '=',
                'refPropertyMapping' => [
                    'path' => '%(path)',
                    'objectMode' => '%(objectmode)',
                    'objectType' => '%(objecttype)',
                    'objectName' => '%(objectname)',
                    'objectSize' => '%(objectsize)',
                ],
            ],
            'assetKey' => 'paths',
        ];

        // @todo Add more cases.
        // - FilePath contains spaces
        // - FilePath ends with spaces.
        return [
            'empty-string' => [
                'expected' => [
                    'branches' => [],
                ],
                'stdOutput' => '',
                'options' => $branchOptions,
            ],
            'branch-basic-single' => [
                'expected' => [
                    'branches' => [
                        'refs/heads/1.x' => [
                            'isCurrentBranch' => true,
                            'push' => 'refs/remotes/upstream/1.x',
                            'push.short' => 'upstream/1.x',
                            'refName' => 'refs/heads/1.x',
                            'refName.short' => '1.x',
                            'track' => '[ahead 1, behind 2]',
                            'track.ahead' => 1,
                            'track.behind' => 2,
                            'track.gone' => false,
                            'upstream' => 'refs/remotes/upstream/1.x',
                            'upstream.short' => 'upstream/1.x',
                        ],
                    ],
                ],
                'stdOutput' => implode(
                    '',
                    [
                        '¤refName=refs/heads/1.x',
                        '×upstream=refs/remotes/upstream/1.x',
                        '×track=[ahead 1, behind 2]',
                        '×push=refs/remotes/upstream/1.x',
                        '×isCurrentBranch=*',
                    ],
                ),
                'options' => $branchOptions,
            ],
            'branch-basic-multiple' => [
                'expected' => [
                    'branches' => [
                        'refs/heads/1.x' => [
                            'isCurrentBranch' => true,
                            'push' => 'refs/remotes/upstream/1.x',
                            'push.short' => 'upstream/1.x',
                            'refName' => 'refs/heads/1.x',
                            'refName.short' => '1.x',
                            'track' => '[ahead 1, behind 2]',
                            'track.ahead' => 1,
                            'track.behind' => 2,
                            'track.gone' => false,
                            'upstream' => 'refs/remotes/upstream/1.x',
                            'upstream.short' => 'upstream/1.x',
                        ],
                        'refs/heads/2.x' => [
                            'isCurrentBranch' => false,
                            'push' => 'refs/remotes/upstream/2.x',
                            'push.short' => 'upstream/2.x',
                            'refName' => 'refs/heads/2.x',
                            'refName.short' => '2.x',
                            'track' => '[ahead 3, behind 4]',
                            'track.ahead' => 3,
                            'track.behind' => 4,
                            'track.gone' => false,
                            'upstream' => 'refs/remotes/upstream/2.x',
                            'upstream.short' => 'upstream/2.x',
                        ],
                    ],
                ],
                'stdOutput' => implode(
                    '',
                    [
                        '¤refName=refs/heads/1.x',
                        '×upstream=refs/remotes/upstream/1.x',
                        '×track=[ahead 1, behind 2]',
                        '×push=refs/remotes/upstream/1.x',
                        '×isCurrentBranch=*',
                        "\n",
                        '¤refName=refs/heads/2.x',
                        '×upstream=refs/remotes/upstream/2.x',
                        '×track=[ahead 3, behind 4]',
                        '×push=refs/remotes/upstream/2.x',
                        '×isCurrentBranch=',
                    ],
                ),
                'options' => $branchOptions,
            ],
            'git-log-nameStatus-single' => [
                'expected' => [
                    'commits' => [
                        'abc' => [
                            'commitHash' => 'abc',
                            'commitMessage.subject' => 'Initial commit',
                            'nameStatus' => [
                                'a.txt' => [
                                    'status' => FileStatus::Renamed,
                                    'filePath' => 'b.txt',
                                    'oldFilePath' => 'a.txt',
                                    'percent' => 100,
                                ],
                                'c.txt' => [
                                    'status' => FileStatus::Added,
                                    'filePath' => 'c.txt',
                                ],
                            ],
                        ],
                    ],
                ],
                'stdOutput' => implode(
                    '',
                    [
                        'commitHash=abc',
                        '×commitMessage.subject=Initial commit',
                        "×nameStatus=\x00",
                        "\n",
                        "R100\x00a.txt\x00b.txt\x00",
                        "A\x00c.txt\x00",
                    ],
                ),
                'options' => $logOptions,
            ],
            'git-log-nameStatus-multiple' => [
                'expected' => [
                    'commits' => [
                        'abc' => [
                            'commitHash' => 'abc',
                            'commitMessage.subject' => 'Initial commit',
                            'nameStatus' => [
                                'a.txt' => [
                                    'status' => FileStatus::Renamed,
                                    'filePath' => 'b.txt',
                                    'oldFilePath' => 'a.txt',
                                    'percent' => 100,
                                ],
                                'c.txt' => [
                                    'status' => FileStatus::Added,
                                    'filePath' => 'c.txt',
                                ],
                            ],
                        ],
                        'def' => [
                            'commitHash' => 'def',
                            'commitMessage.subject' => 'My commit 02',
                            'nameStatus' => [
                                'a.txt' => [
                                    'status' => FileStatus::Changed,
                                    'filePath' => 'a.txt',
                                ],
                                'c.txt' => [
                                    'status' => FileStatus::Added,
                                    'filePath' => 'c.txt',
                                ],
                            ],
                        ],
                    ],
                ],
                'stdOutput' => implode(
                    '',
                    [
                        '¤commitHash=abc',
                        '×commitMessage.subject=Initial commit',
                        "×nameStatus=\x00",
                        "\n",
                        "R100\x00a.txt\x00b.txt\x00",
                        "A\x00c.txt\x00",

                        '¤commitHash=def',
                        '×commitMessage.subject=My commit 02',
                        "×nameStatus=\x00",
                        "\n",
                        "M\x00a.txt\x00",
                        "A\x00c.txt\x00",
                    ],
                ),
                'options' => $logOptions,
            ],
            'git-ls-tree.multiple' => [
                'expected' => [
                    'paths' => [
                        'a.php' => [
                            'objectMode' => '100644',
                            'objectName' => 'name01',
                            'objectSize' => 21,
                            'objectType' => 'blob',
                            'path' => 'a.php',
                        ],
                        'b.php' => [
                            'objectMode' => '100644',
                            'objectName' => 'name02',
                            'objectSize' => 42,
                            'objectType' => 'blob',
                            'path' => 'b.php',
                        ],
                        'src' => [
                            'objectMode' => '100755',
                            'objectName' => 'name03',
                            'objectSize' => null,
                            'objectType' => 'tree',
                            'path' => 'src',
                        ],
                    ],
                ],
                'stdOutput' => implode(
                    '',
                    [
                        'path=a.php',
                        '|objectMode=100644',
                        '|objectType=blob',
                        '|objectName=name01',
                        '|objectSize=21',
                        "\0",
                        'path=b.php',
                        '|objectMode=100644',
                        '|objectType=blob',
                        '|objectName=name02',
                        '|objectSize=42',
                        "\0",
                        'path=src',
                        '|objectMode=100755',
                        '|objectType=tree',
                        '|objectName=name03',
                        '|objectSize=-',
                    ],
                ),
                'options' => $lsTreeOptions,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $options
     */
    #[Test]
    #[DataProvider('casesParseSuccessful')]
    public function testParseSuccessful(
        array $expected,
        int $exitCode = 0,
        string $stdOutput = '',
        string $stdError = '',
        array $options = [],
    ): void {
        $parser = new FormatParser();
        $actual = $parser->parse($exitCode, $stdOutput, $stdError, $options);
        static::assertSame($expected, $actual);
    }
}
