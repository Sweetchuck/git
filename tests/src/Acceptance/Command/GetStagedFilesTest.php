<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetStagedFiles;
use Sweetchuck\Git\FilePathStyle;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParser\GetChangedFilesParser;

#[CoversClass(GetStagedFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetChangedFilesParser::class)]
#[Group('command-git-diff')]
class GetStagedFilesTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init --initial-branch="main" {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [],
            ],
            'staged-added-file' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file1.txt' => [
                                'filePath' => 'file1.txt',
                                'status' => FileStatus::Added,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'Hello',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file1.txt',
                    ],
                ],
                'properties' => [],
            ],
            'staged-deleted-file' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file2.txt' => [
                                'filePath' => 'file2.txt',
                                'status' => FileStatus::Deleted,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'To be deleted',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "add file2"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git rm file2.txt',
                    ],
                ],
                'properties' => [],
            ],
            'staged-modified-file' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file3.txt' => [
                                'filePath' => 'file3.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file3.txt',
                        'content' => 'Initial',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file3.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "add file3"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file3.txt',
                        'content' => 'Changed',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file3.txt',
                    ],
                ],
                'properties' => [],
            ],
            'multiple-staged-files' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file4.txt' => [
                                'filePath' => 'file4.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                            'file5.txt' => [
                                'filePath' => 'file5.txt',
                                'status' => FileStatus::Deleted,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file4.txt',
                        'content' => 'A',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file5.txt',
                        'content' => 'B',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file4.txt file5.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "add files"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git rm file5.txt',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file4.txt',
                        'content' => 'A changed',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file4.txt',
                    ],
                ],
                'properties' => [],
            ],
            'with-diffFilter' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file6.txt' => [
                                'filePath' => 'file6.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                            'file7.txt' => [
                                'filePath' => 'file7.txt',
                                'status' => FileStatus::Unmerged,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file6.txt',
                        'content' => 'A',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file7.txt',
                        'content' => 'B',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file8.txt',
                        'content' => 'To be deleted',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file6.txt file7.txt file8.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit --message="Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file6.txt',
                        'content' => 'A-changed',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file7.txt',
                        'content' => 'B-changed',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git rm file8.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                ],
                'properties' => [
                    'diffFilter' => [
                        'M' => true,
                        'D' => false,
                    ],
                ],
            ],
            'with-paths-filter' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'include/file8.txt' => [
                                'filePath' => 'include/file8.txt',
                                'status' => FileStatus::Added,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'mkdir -p {{ dirSafe }}/include {{ dirSafe }}/exclude',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/include/file8.txt',
                        'content' => 'A',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/exclude/file9.txt',
                        'content' => 'B',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add include/file8.txt exclude/file9.txt',
                    ],
                ],
                'properties' => [
                    'paths' => [
                        'include' => true,
                    ],
                ],
            ],
            'with-filePathStyle-absolute' => [
                // @todo initSteps and expectations.
                'expected' => [
                    'artifacts' => [
                        'files' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [
                    'filePathStyle' => FilePathStyle::Absolute,
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

        $command = new GetStagedFiles();
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
