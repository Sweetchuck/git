<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\FileStatus;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\OutcomeParser\GetBranchesParser;

#[CoversClass(GetBranchesParser::class)]
class GetBranchesParserTest extends TestCase
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
                    'currentBranch' => null,
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
                            'isDetached' => false,
                        ],
                    ],
                    'currentBranch' => 'refs/heads/1.x',
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
                            'isDetached' => false,
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
                            'isDetached' => false,
                        ],
                    ],
                    'currentBranch' => 'refs/heads/1.x',
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
            'branch-detached-multiple' => [
                'expected' => [
                    'branches' => [
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
                            'isDetached' => false,
                        ],
                        'abcd123' => [
                            'isCurrentBranch' => true,
                            'push' => null,
                            'push.short' => null,
                            'refName' => 'abcd123',
                            'refName.short' => 'abcd123',
                            'track' => null,
                            'track.ahead' => null,
                            'track.behind' => null,
                            'track.gone' => false,
                            'upstream' => null,
                            'upstream.short' => null,
                            'isDetached' => true,
                        ],
                    ],
                    'currentBranch' => 'abcd123',
                ],
                'stdOutput' => implode(
                    '',
                    [
                        '¤refName=(HEAD detached at abcd123)',
                        '×upstream=',
                        '×track=',
                        '×push=',
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
        $parser = new GetBranchesParser();
        $actual = $parser->parse($exitCode, $stdOutput, $stdError, $options);
        static::assertSame($expected, $actual);
    }
}
