<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetStagedFiles;
use Sweetchuck\Git\FilePathStyle;
use Sweetchuck\Git\FileStatus;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Struct\ChangedFile;

#[CoversClass(GetStagedFiles::class)]
#[Group('command-git-diff')]
class GetStagedFilesTest extends CommandTestBase
{

    protected function createCommand(): GetStagedFiles
    {
        return new GetStagedFiles();
    }

    public static function casesGetCliCommand(): array
    {
        $defaults = [
            '--no-pager',
            'diff',
            '--no-color',
            '--name-status',
            '--cached',
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
            'all-in-one' => [
                'expected' => [
                    'git',
                    ...$defaults,
                    '--diff-filter=Ab',
                    '--relative',
                    '--',
                    '*.php',
                    '**/*.php',
                ],
                'properties' => [
                    'diffFilter' => [
                        'A' => true,
                        'B' => false,
                    ],
                    'filePathStyle' => FilePathStyle::RelativeToWorkingDirectory,
                    'paths' => [
                        '*.php' => true,
                        '**/*.php' => true,
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
            'relative to top level' => [
                'expected' => [
                    'artifacts' => [
                        'files' => [
                            'file1.php' => new ChangedFile('file1.php', FileStatus::Added),
                            'file2.php' => new ChangedFile('file2.php', FileStatus::Unmerged),
                        ],
                    ],
                ],
                'properties' => [
                    'filePathStyle' => FilePathStyle::RelativeToTopLevel,
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
}
