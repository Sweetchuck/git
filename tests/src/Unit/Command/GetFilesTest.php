<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetFiles;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\FileStatus;

#[CoversClass(GetFiles::class)]
#[Group('command-git-ls-files')]
class GetFilesTest extends CommandTestBase
{

    protected function createCommand(): GetFiles
    {
        return new GetFiles();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'all in one' => [
                'expected' => [
                    'git',
                    'ls-files',
                    '-z',
                    '-t',
                    '--eol',
                    '--cached',
                    '--deleted',
                    '--modified',
                    '--ignored',
                    '--directory',
                    '--no-empty-directory',
                    '--killed',
                ],
                'properties' => [
                    'cached' => true,
                    'deleted' => true,
                    'modified' => true,
                    'ignored' => true,
                    'directory' => true,
                    'noEmptyDirectory' => true,
                    'killed' => true,
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
                    'artifacts' => [],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => '',
                    ],
                ],
            ],
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'tracked.php' => [
                            'status' => FileStatus::Tracked,
                            'statusChar' => 'H',
                            'path' => 'tracked.php',
                            'attributes' => [
                                'i' => 'lf',
                                'w' => 'lf',
                                'attr' => '',
                            ],
                        ],
                        'untracked.php' => [
                            'status' => FileStatus::Untracked,
                            'statusChar' => '?',
                            'path' => 'untracked.php',
                            'attributes' => [
                                'i' => 'lf',
                                'w' => 'lf',
                                'attr' => '',
                            ],
                        ],
                        'unmerged.php' => [
                            'status' => FileStatus::Unmerged,
                            'statusChar' => 'M',
                            'path' => 'unmerged.php',
                            'attributes' => [
                                'i' => 'lf',
                                'w' => 'lf',
                                'attr' => '',
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
                                "H i/lf    w/lf    attr/                 \ttracked.php\0",
                                "? i/lf    w/lf    attr/                 \tuntracked.php\0",
                                "M i/lf    w/lf    attr/                 \tunmerged.php\0",
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
