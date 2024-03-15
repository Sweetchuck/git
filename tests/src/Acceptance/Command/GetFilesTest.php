<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetFiles;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParser\GetFilesParser;

#[CoversClass(GetFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetFilesParser::class)]
#[Group('command-git-ls-files')]
class GetFilesTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                ],
                'properties' => [],
            ],
            'basic-files' => [
                'expected' => [
                    'artifacts' => [
                        'file1.txt' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'file1.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                        'file2.txt' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'file2.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file1.txt file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add test files"',
                    ],
                ],
                'properties' => [],
            ],
            'with-directories' => [
                'expected' => [
                    'artifacts' => [
                        'dir1/file3.txt' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'dir1/file3.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                        'dir2/file4.txt' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'dir2/file4.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir dir1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir dir2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch dir1/file3.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch dir2/file4.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add dir1/file3.txt dir2/file4.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add files in directories"',
                    ],
                ],
                'properties' => [],
            ],
            'with-modified-option' => [
                'expected' => [
                    'artifacts' => [
                        'modified-file.txt' => [
                            'status' => FileStatus::UnstagedModification,
                            'statusChar' => 'C',
                            'path' => 'modified-file.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch modified-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add modified-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add file"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified-file.txt',
                        'content' => 'Modified content',
                    ],
                ],
                'properties' => [
                    'modified' => true,
                ],
            ],
            'with-others-option' => [
                'expected' => [
                    'artifacts' => [
                        'untracked-file.txt' => [
                            'status' => FileStatus::Untracked,
                            'statusChar' => '?',
                            'path' => 'untracked-file.txt',
                            'attributes' => [
                                'i' => '',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch untracked-file.txt',
                    ],
                ],
                'properties' => [
                    'others' => true,
                ],
            ],
            'with-paths-filter' => [
                'expected' => [
                    'artifacts' => [
                        'include/file1.txt' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'include/file1.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir include',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir exclude',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch include/file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch exclude/file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add include/file1.txt exclude/file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add files"',
                    ],
                ],
                'properties' => [
                    'paths' => ['include'],
                ],
            ],
            'with-cached-option' => [
                'expected' => [
                    'artifacts' => [
                        'staged-file.txt' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'staged-file.txt',
                            'attributes' => [
                                'i' => 'none',
                                'w' => 'none',
                                'attr' => '',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch staged-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch unstaged-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add staged-file.txt',
                    ],
                ],
                'properties' => [
                    'cached' => true,
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new GetFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
