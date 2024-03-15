<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetChangedFiles;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParser\GetChangedFilesParser;

#[CoversClass(GetChangedFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetChangedFilesParser::class)]
#[Group('command-git-status')]
class GetChangedFilesTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                ],
                'properties' => [],
            ],
            'working-tree-changes' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'modified-file.txt' => [
                                'filePath' => 'modified-file.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified-file.txt',
                        'content' => 'Initial content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add modified-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified-file.txt',
                        'content' => 'Modified content',
                    ],
                ],
                'properties' => [],
            ],
            'with-diffFilter' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'modified-file.txt' => [
                                'filePath' => 'modified-file.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                        ],
                    ],
                ],
                // @todo Better status.
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/added-file.txt',
                        'content' => 'New file content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified-file.txt',
                        'content' => 'Initial content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified-file.txt',
                        'content' => 'Modified content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/new-file.txt',
                        'content' => 'New file content',
                    ],
                ],
                'properties' => [
                    'diffFilter' => [
                        'A' => false,
                        'M' => true,
                    ],
                ],
            ],
            'between-commits' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file2.txt' => [
                                'filePath' => 'file2.txt',
                                'status' => FileStatus::Added,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'File 1 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "First commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'File 2 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Second commit"',
                    ],
                ],
                'properties' => [
                    'commandArguments' => ['HEAD~1', 'HEAD'],
                ],
            ],
            'with-merge-base' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'feature-file.txt' => [
                                'filePath' => 'feature-file.txt',
                                'status' => FileStatus::Added,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/main-file.txt',
                        'content' => 'Main file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add main-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Main branch commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout -b feature-branch',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/feature-file.txt',
                        'content' => 'Feature file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add feature-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Feature branch commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout 1.x',
                    ],
                ],
                'properties' => [
                    'mergeBase' => true,
                    'commandArguments' => ['1.x', 'feature-branch'],
                ],
            ],
            'with-no-index' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'dir1/file.txt' => [
                                'filePath' => 'dir1/file.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir1/file.txt',
                        'content' => 'File content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir2/file.txt',
                        'content' => 'Different content',
                    ],
                ],
                'properties' => [
                    'noIndex' => true,
                    'paths' => ['dir1', 'dir2'],
                ],
            ],
            'with-paths-filter' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'include/file.txt' => [
                                'filePath' => 'include/file.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'mkdir -p {{ dirSafe }}/include {{ dirSafe }}/exclude',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/include/file.txt',
                        'content' => 'Initial content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/exclude/file.txt',
                        'content' => 'Initial content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/include/file.txt',
                        'content' => 'Modified content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/exclude/file.txt',
                        'content' => 'Modified content',
                    ],
                ],
                'properties' => [
                    'paths' => [
                        'include' => true,
                    ],
                ],
            ],
            'deleted-files' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'deleted-file.txt' => [
                                'filePath' => 'deleted-file.txt',
                                'status' => FileStatus::Deleted,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch=1.x {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/deleted-file.txt',
                        'content' => 'Content to be deleted',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add deleted-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add file"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && rm deleted-file.txt',
                    ],
                ],
                'properties' => [],
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

        $command = new GetChangedFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (isset($expected['artifacts']['files'])) {
            $this->assertFilesMatch($expected['artifacts']['files'], $result->artifacts['files']);
        }
    }

    /**
     * @param array<string, array<string, mixed>> $expected
     * @param array<string, \Sweetchuck\Git\Struct\ChangedFile> $actual
     */
    protected function assertFilesMatch(array $expected, array $actual): void
    {
        static::assertSame(
            array_keys($expected),
            array_keys($actual),
            'File paths should match',
        );

        foreach ($expected as $path => $expectedFile) {
            $actualFile = $actual[$path];

            static::assertSame(
                $expectedFile['filePath'],
                $actualFile->fileName,
                "File name should match for $path",
            );

            static::assertSame(
                $expectedFile['status'],
                $actualFile->status,
                "File status should match for $path",
            );
        }
    }
}
