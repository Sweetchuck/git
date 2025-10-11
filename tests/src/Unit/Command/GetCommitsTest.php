<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\GetCommits;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\Tests\Helper\DummyUniqueIdGenerator;

#[CoversClass(GetCommits::class)]
#[Group('command-git-log')]
class GetCommitsTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        $uniqueIdGenerator = new DummyUniqueIdGenerator();
        $command = new GetCommits();
        $command->getFormatHandler()->setUniqueIdGenerator($uniqueIdGenerator);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        $expectedFormatDefault = '--format=¤';
        $expectedFormatDefault .= implode(
            '×',
            [
                'commitHash=%H',
                'commitHash.short=%h',
                'treeHash=%T',
                'treeHash.short=%t',
                'parentHashes=%P',
                'authorName=%an',
                'authorName.mailMap=%aN',
                'authorEmail=%ae',
                'authorEmail.mailMap=%aE',
                'authorDate=%ad',
                'committerName=%cn',
                'committerName.mailMap=%cN',
                'committerEmail=%ce',
                'committerEmail.mailMap=%cE',
                'committerDate=%cd',
                'commitNotes=%cN',
                'refNames=%D',
                'commitMessage.subject=%s',
                'commitMessage.body=%b',
                'commitMessage.full=%B',
                'nameStatus=',
            ],
        );

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                ],
                'properties' => [],
            ],
            'with maxCount' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--max-count=10',
                ],
                'properties' => [
                    'maxCount' => 10,
                ],
            ],
            'with author' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--author=John Doe',
                ],
                'properties' => [
                    'author' => 'John Doe',
                ],
            ],
            'with since date' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--since=2023-01-01',
                ],
                'properties' => [
                    'since' => '2023-01-01',
                ],
            ],
            'with until date' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--until=2023-12-31',
                ],
                'properties' => [
                    'until' => '2023-12-31',
                ],
            ],
            'with skip commits' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--skip=5',
                ],
                'properties' => [
                    'skip' => 5,
                ],
            ],
            'with committer' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--committer=Jane Smith',
                ],
                'properties' => [
                    'committer' => 'Jane Smith',
                ],
            ],
            'with paths' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--',
                    'src/',
                    'tests/',
                ],
                'properties' => [
                    'paths' => ['src/', 'tests/'],
                ],
            ],
            'with multiple options' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--author=John Doe',
                    '--max-count=20',
                    '--since=2023-01-01',
                    '--',
                    'src/',
                ],
                'properties' => [
                    'author' => 'John Doe',
                    'since' => '2023-01-01',
                    'maxCount' => 20,
                    'paths' => ['src/'],
                ],
            ],
            'with follow enabled' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--follow',
                ],
                'properties' => [
                    'follow' => true,
                ],
            ],
            'with all branches' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--all',
                ],
                'properties' => [
                    'all' => true,
                ],
            ],
            'with global options' => [
                'expected' => [
                    'git',
                    '-C',
                    'my-dir',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                ],
                'properties' => [
                    'cwd' => 'my-dir',
                ],
            ],
            'all-in-one' => [
                'expected' => [
                    'git',
                    'log',
                    '-z',
                    '--no-color',
                    '--date=iso',
                    '--name-status',
                    $expectedFormatDefault,
                    '--all',
                    '--sort=a',
                    '--sort=c',
                    '--perl-regexp',
                    '--follow',
                    '--author=a1',
                    '--committer=c2',
                    '--max-count=3',
                    '--skip=2',
                    '--since=s4',
                    '--until=u5',
                ],
                'properties' => [
                    'all' => true,
                    'sort' => [
                        'a' => true,
                        'b' => false,
                        'c' => true,
                    ],
                    'patternType' => 'perl',
                    'follow' => true,
                    'author' => 'a1',
                    'committer' => 'c2',
                    'maxCount' => 3,
                    'skip' => 2,
                    'since' => 's4',
                    'until' => 'u5',
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
            'empty' => [
                'expected' => [
                    'artifacts' => [
                        'commits' => [
                            'ch-01' => [
                                'commitHash' => 'ch-01',
                                'commitMessage.subject' => 'cmS-01',
                                'nameStatus' => [
                                    'src/Option/OptionPathSpecFromFileTrait.php' => [
                                        'status' => FileStatus::Changed,
                                        'filePath' => 'src/Option/OptionPathSpecFromFileTrait.php',
                                    ],
                                ],
                            ],
                            'ch-02' => [
                                'commitHash' => 'ch-02',
                                'commitMessage.subject' => 'cmS-02',
                                'nameStatus' => [
                                    'src/Option/OptionIgnoreUnmatchTrait.php' => [
                                        'status' => FileStatus::Added,
                                        'filePath' => 'src/Option/OptionIgnoreUnmatchTrait.php',
                                    ],
                                    'src/Option/OptionPathSpecFileNulTrait.php' => [
                                        'status' => FileStatus::Deleted,
                                        'filePath' => 'src/Option/OptionPathSpecFileNulTrait.php',
                                    ],
                                    'src/Option/OptionPathSpecFromFileTrait.php' => [
                                        'status' => FileStatus::Changed,
                                        'filePath' => 'src/Option/OptionPathSpecFromFileTrait.php',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                '¤commitHash=ch-01',
                                '×commitMessage.subject=cmS-01',
                                "×nameStatus=\x00",
                                "\n",
                                "M\x00src/Option/OptionPathSpecFromFileTrait.php\x00",
                                "\x00",
                                '¤commitHash=ch-02',
                                '×commitMessage.subject=cmS-02',
                                "×nameStatus=\x00",
                                "\n",
                                "\n",
                                "A\x00src/Option/OptionIgnoreUnmatchTrait.php\x00",
                                "D\x00src/Option/OptionPathSpecFileNulTrait.php\x00",
                                "M\x00src/Option/OptionPathSpecFromFileTrait.php\x00",
                            ],
                        ),
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
