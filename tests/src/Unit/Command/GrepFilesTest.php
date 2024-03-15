<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\GrepFiles;

#[CoversClass(GrepFiles::class)]
#[Group('command-git-grep')]
class GrepFilesTest extends CommandTestBase
{

    protected function createCommand(): GrepFiles
    {
        return new GrepFiles();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                ['git', 'grep', '--color=never', '--null', '--line', '--column'],
                [],
            ],
            'context-both' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--context=3',
                ],
                [
                    'context' => 3,
                ],
            ],
            'context-before' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--before-context=2',
                ],
                [
                    'beforeContext' => 2,
                ],
            ],
            'context-after' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--after-context=2',
                ],
                [
                    'afterContext' => 2,
                ],
            ],
            'context-before-after-same' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--context=2',
                ],
                [
                    'beforeContext' => 2,
                    'afterContext' => 2,
                ],
            ],
            'context-before-after-different' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--before-context=4',
                    '--after-context=2',
                ],
                [
                    'beforeContext' => 4,
                    'afterContext' => 2,
                ],
            ],
            'context-pattern-type-basic' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--basic-regexp',
                ],
                [
                    'patternType' => 'basic',
                ],
            ],
            'context-pattern-type-extended' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--extended-regexp',
                ],
                [
                    'patternType' => 'extended',
                ],
            ],
            'context-pattern-type-perl' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--perl-regexp',
                ],
                [
                    'patternType' => 'perl',
                ],
            ],
            'all-in-one-01' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--files-with-matches',
                    '--cached',
                    '--untracked',
                    '--no-index',
                    '--exclude-standard',
                    '--recurse-submodules',
                    '--text',
                    '--textconv',
                    '--ignore-case',
                    '-I',
                    '--max-depth=42',
                    '--invert-match',
                    '--word-regexp',
                    '--full-name',
                    '--basic-regexp',
                    '--only-matching',
                    '--show-function',
                    '--context=5',
                    '--max-count=6',
                    '--threads=7',
                    '--all-match',
                    '-f', 'my-patterns.txt',
                ],
                [
                    'filesWithMatches' => true,
                    'cached' => true,
                    'untracked' => true,
                    'noIndex' => true,
                    'excludeStandard' => true,
                    'recurseSubmodules' => [true],
                    'text' => true,
                    'textConv' => true,
                    'ignoreCase' => true,
                    'ignoreBinaryFiles' => true,
                    'maxDepth' => 42,
                    'invertMatch' => true,
                    'wordRegexp' => true,
                    'fullName' => true,
                    'patternType' => 'basic',
                    'onlyMatching' => true,
                    'showFunction' => true,
                    'context' => 5,
                    'maxCount' => 6,
                    'threads' => 7,
                    'allMatch' => true,
                    'file' => 'my-patterns.txt',
                ],
            ],
            'all-in-one-02' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--files-without-match',
                    '--no-exclude-standard',
                    '--no-recurse-submodules',
                    '--no-ignore-case',
                ],
                [
                    'filesWithMatches' => false,
                    'excludeStandard' => false,
                    'recurseSubmodules' => [false],
                    'ignoreCase' => false,
                ],
            ],
            'tree-basic' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    'my-branch-01',
                ],
                [
                    'tree' => 'my-branch-01',
                ],
            ],
            'paths-basic' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '--',
                    ':^Documentation',
                ],
                [
                    'paths' => [
                        ':^Documentation' => true,
                        'do-not-filter-me' => false,
                    ],
                ],
            ],
            'expressions 01' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '-e', 'p01',
                    'my-branch-01',
                ],
                [
                    'expressions' => ['p01'],
                    'tree' => 'my-branch-01',
                ],
            ],
            'expressions 02' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '-e', 'p01',
                    '-e', 'p02',
                    'my-branch-01',
                ],
                [
                    'expressions' => ['p01', 'p02'],
                    'tree' => 'my-branch-01',
                ],
            ],
            'expressions 03' => [
                [
                    'git',
                    'grep',
                    '--color=never',
                    '--null',
                    '--line',
                    '--column',
                    '-e', 'p01',
                    '-e', 'p02',
                    '--and', '(', '-e', 'p03', '-e', 'p04', ')',
                    'my-branch-01',
                ],
                [
                    'expressions' => [
                        'p01',
                        'p02',
                        [
                            'operator' => 'and',
                            'patterns' => [
                                'p03',
                                'p04',
                            ],
                        ],
                    ],
                    'tree' => 'my-branch-01',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic - empty' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => "\n",
                    ],
                ],
            ],
            'basic - default' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'a.php' => [
                                'lines' => [
                                    2 => 'l02',
                                    3 => 'l03',
                                    4 => 'l04',
                                ],
                                'matches' => [
                                    3 => 2,
                                ],
                            ],
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => <<<TEXT
                            a.php\x002\x00l02
                            a.php\x003\x002\x00l03
                            a.php\x004\x00l04

                            TEXT,
                    ],
                ],
            ],
            'basic - onlyFilePaths' => [
                'expected' => [
                    'artifacts' => [
                        'filePaths' => [
                            'a.php',
                            'b.php',
                        ],
                    ],
                ],
                'properties' => [
                    'filesWithMatches' => true,
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => "a.php\x00b.php",
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<array<string, mixed>> $processOutcomes
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(array $expected, array $properties, array $processOutcomes = []): void
    {
        if (!array_key_exists('processFactory', $properties)) {
            $properties['processFactory'] = $this->createProcessFactory($processOutcomes);
        }
        $command = $this->createCommand();
        $command->setProperties($properties);

        $result = $command->execute();
        if (isset($expected['exitCode'])) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (isset($expected['stdOutput'])) {
            static::assertSame($expected['stdOutput'], $result->process->getOutput());
        }

        if (isset($expected['stdError'])) {
            static::assertSame($expected['stdError'], $result->process->getErrorOutput());
        }

        if (isset($expected['artifacts'])) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
