<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetChangedFiles;
use Sweetchuck\Git\FilePathStyle;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\Struct\ChangedFile;

#[CoversClass(GetChangedFiles::class)]
#[Group('command-git-status')]
class GetChangedFilesTest extends CommandTestBase
{
    protected function createCommand(): GetChangedFiles
    {
        return new GetChangedFiles();
    }

    public static function casesGetCliCommand(): array
    {
        $defaults = [
            '--no-pager',
            'diff',
            '--no-color',
            '--name-status',
            '-z',
        ];

        return [
            'only defaults' => [
                'expected' => [
                    'git',
                    ...$defaults,
                ],
                'properties' => [],
            ],
            'with mergeBase' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--merge-base',
                ],
                'properties' => [
                    'mergeBase' => true,
                ],
            ],
            'with noIndex' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--no-index',
                ],
                'properties' => [
                    'noIndex' => true,
                ],
            ],
            'with commandArguments' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    'HEAD',
                    'HEAD~1',
                ],
                'properties' => [
                    'commandArguments' => [
                        'HEAD',
                        'HEAD~1',
                    ],
                ],
            ],
            'with diffFilter' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--diff-filter=AMd',
                ],
                'properties' => [
                    'diffFilter' => [
                        'A' => true,
                        'M' => true,
                        'D' => false,
                    ],
                ],
            ],
            'with paths' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--',
                    '*.php',
                    '**/*.js',
                ],
                'properties' => [
                    'paths' => [
                        '*.php' => true,
                        '**/*.js' => true,
                    ],
                ],
            ],
            'with filePathStyle' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--relative',
                ],
                'properties' => [
                    'filePathStyle' => FilePathStyle::RelativeToWorkingDirectory,
                ],
            ],
            'all options combined' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--diff-filter=AMd',
                    '--relative',
                    '--merge-base',
                    '--no-index',
                    'HEAD',
                    'HEAD~1',
                    '--',
                    '*.php',
                ],
                'properties' => [
                    'mergeBase' => true,
                    'noIndex' => true,
                    'diffFilter' => [
                        'A' => true,
                        'M' => true,
                        'D' => false,
                    ],
                    'filePathStyle' => FilePathStyle::RelativeToWorkingDirectory,
                    'commandArguments' => [
                        'HEAD',
                        'HEAD~1',
                    ],
                    'paths' => [
                        '*.php' => true,
                    ],
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
                        'files' => [],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            './file1.php' => new ChangedFile('./file1.php', FileStatus::Added),
                            './file2.php' => new ChangedFile('./file2.php', FileStatus::Unmerged),
                            './file3.php' => new ChangedFile('./file3.php', FileStatus::Deleted),
                        ],
                    ],
                ],
                'properties' => [
                    'filePathStyle' => FilePathStyle::RelativeToWorkingDirectory,
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode('', [
                            "A\0file1.php\0",
                            "M\0file2.php\0",
                            "D\0file3.php\0",
                        ]),
                        'stdError' => '',
                    ],
                ],
            ],
            'with mergeBase' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file1.php' => new ChangedFile('file1.php', FileStatus::Added),
                            'file2.php' => new ChangedFile('file2.php', FileStatus::Unmerged),
                        ],
                    ],
                ],
                'properties' => [
                    'mergeBase' => true,
                    'commandArguments' => ['HEAD', 'feature-branch'],
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode('', [
                            "A\0file1.php\0",
                            "M\0file2.php\0",
                        ]),
                        'stdError' => '',
                    ],
                ],
            ],
            'with noIndex' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file1.txt' => new ChangedFile('file1.txt', FileStatus::Added),
                        ],
                    ],
                ],
                'properties' => [
                    'noIndex' => true,
                    'commandArguments' => ['dir1', 'dir2'],
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode('', [
                            "A\0file1.txt\0",
                        ]),
                        'stdError' => '',
                    ],
                ],
            ],
            'with diffFilter' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file1.php' => new ChangedFile('file1.php', FileStatus::Added),
                            'file2.php' => new ChangedFile('file2.php', FileStatus::Unmerged),
                        ],
                    ],
                ],
                'properties' => [
                    'diffFilter' => [
                        'A' => true,
                        'M' => true,
                        'D' => false,
                    ],
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode('', [
                            "A\0file1.php\0",
                            "M\0file2.php\0",
                        ]),
                        'stdError' => '',
                    ],
                ],
            ],
            'absolute path' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            '/path/to/repo/file1.php' => new ChangedFile(
                                '/path/to/repo/file1.php',
                                FileStatus::Added,
                            ),
                        ],
                    ],
                ],
                'properties' => [
                    'filePathStyle' => FilePathStyle::Absolute,
                    'topLevel' => '/path/to/repo',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode('', [
                            "A\0file1.php\0",
                        ]),
                        'stdError' => '',
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

        if (isset($expected['artifacts']['files'])) {
            static::assertSame(
                array_keys($expected['artifacts']['files']),
                array_keys($result->artifacts['files']),
            );

            foreach ($expected['artifacts']['files'] as $path => $expectedFile) {
                $actualFile = $result->artifacts['files'][(string) $path];
                static::assertSame($expectedFile->fileName, $actualFile->fileName);
                static::assertSame($expectedFile->status, $actualFile->status);
            }
        }
    }

    #[Test]
    public function testMergeBaseGetterSetter(): void
    {
        $command = $this->createCommand();

        // Default value should be null
        static::assertNull($command->getMergeBase());

        // Test setter and getter
        $command->setMergeBase(true);
        static::assertTrue($command->getMergeBase());

        $command->setMergeBase(false);
        static::assertFalse($command->getMergeBase());

        $command->setMergeBase(null);
        static::assertNull($command->getMergeBase());
    }

    #[Test]
    public function testNoIndexGetterSetter(): void
    {
        $command = $this->createCommand();

        // Default value should be null
        static::assertNull($command->getNoIndex());

        // Test setter and getter
        $command->setNoIndex(true);
        static::assertTrue($command->getNoIndex());

        $command->setNoIndex(false);
        static::assertFalse($command->getNoIndex());

        $command->setNoIndex(null);
        static::assertNull($command->getNoIndex());
    }

    #[Test]
    public function testCommandArgumentsGetterSetter(): void
    {
        $command = $this->createCommand();

        // Default value should be empty array
        static::assertSame([], $command->getCommandArguments());

        // Test setter and getter
        $args = ['HEAD', 'HEAD~1'];
        $command->setCommandArguments($args);
        static::assertSame($args, $command->getCommandArguments());

        // Test adding a single argument
        $command->addCommandArgument('feature-branch');
        static::assertSame([...$args, 'feature-branch'], $command->getCommandArguments());
    }
}
