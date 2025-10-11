<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetFilesInWorkingCopy;
use Sweetchuck\Git\OutcomeParser\GetFilesInWorkingCopyParser;

#[CoversClass(GetFilesInWorkingCopy::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetFilesInWorkingCopyParser::class)]
#[Group('command-git-ls-files')]
class GetFilesInWorkingCopyTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init --initial-branch='main' {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email 'test@example.com' \
                && git config user.name  'Test User'
                SHELL,
        ];

        return [
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [],
            ],
            'basic-files' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'file1.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'none',
                                'eolInfoWorkTree' => 'none',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 0,
                                'objectType' => 'blob',
                                'path' => 'file1.txt',
                                'stage' => '0',
                            ],
                            'file2.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'none',
                                'eolInfoWorkTree' => 'none',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 0,
                                'objectType' => 'blob',
                                'path' => 'file2.txt',
                                'stage' => '0',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && touch file1.txt \
                            && touch file2.txt
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add file1.txt file2.txt \
                            && git commit --message='Add test files'
                            SHELL,
                    ],
                ],
                'properties' => [],
            ],
            'with-directories' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'dir1/file3.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'lf',
                                'eolInfoWorkTree' => 'lf',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 14,
                                'objectType' => 'blob',
                                'path' => 'dir1/file3.txt',
                                'stage' => '0',
                            ],
                            'dir2/file4.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'lf',
                                'eolInfoWorkTree' => 'lf',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 14,
                                'objectType' => 'blob',
                                'path' => 'dir2/file4.txt',
                                'stage' => '0',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && mkdir dir1 \
                            && touch dir1/file3.txt \
                            && echo 'Line 1' >  dir1/file3.txt \
                            && echo 'Line 2' >> dir1/file3.txt
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && mkdir dir2 \
                            && touch dir2/file4.txt \
                            && echo 'Line 1' >  dir2/file4.txt \
                            && echo 'Line 2' >> dir2/file4.txt
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add dir1/file3.txt dir2/file4.txt \
                            && git commit --message='Add files in directories'
                            SHELL,
                    ],
                ],
                'properties' => [],
            ],
            'with-modified-option' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'modified-file.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'none',
                                'eolInfoWorkTree' => 'none',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 0,
                                'objectType' => 'blob',
                                'path' => 'modified-file.txt',
                                'stage' => '0',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && touch modified-file.txt \
                            && git add modified-file.txt \
                            && git commit --message='Add file'
                            SHELL,
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
            'with-paths-filter' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'include/file1.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'none',
                                'eolInfoWorkTree' => 'none',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 0,
                                'objectType' => 'blob',
                                'path' => 'include/file1.txt',
                                'stage' => '0',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && mkdir include exclude \
                            && touch include/file1.txt \
                            && touch exclude/file2.txt
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add include/file1.txt exclude/file2.txt \
                            && git commit --message='Add files'
                            SHELL,
                    ],
                ],
                'properties' => [
                    'paths' => ['include'],
                ],
            ],
            'with-cached-option' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'staged-file.txt' => [
                                'eolAttributes' => null,
                                'eolInfoIndex' => 'none',
                                'eolInfoWorkTree' => 'none',
                                'objectMode' => '100644',
                                'objectName' => static::expectString(),
                                'objectSize' => 0,
                                'objectType' => 'blob',
                                'path' => 'staged-file.txt',
                                'stage' => '0',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && touch staged-file.txt \
                            && touch unstaged-file.txt \
                            && git add staged-file.txt
                            SHELL,
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
        $expected += [
            'exitCode' => 0,
        ];

        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new GetFilesInWorkingCopy();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame(
                $expected['exitCode'],
                $result->process->getExitCode(),
                sprintf(
                    "exit code match\n--== stdError BEGIN ==--\n%s\n--== stdError END ==--",
                    $result->process->getErrorOutput(),
                ),
            );
        }

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame(
                array_keys($expected['artifacts']),
                array_keys($result->artifacts),
                'artifacts keys match',
            );

            if (array_key_exists('paths', $expected['artifacts'])) {
                static::assertSame(
                    array_keys($expected['artifacts']['paths']),
                    array_keys($result->artifacts['paths']),
                    'artifacts.paths keys match',
                );

                foreach ($expected['artifacts']['paths'] as $path => $expectedData) {
                    $actualData = $result->artifacts['paths'][$path];
                    static::assertSame(
                        array_keys($expectedData),
                        array_keys($actualData),
                        "artifacts.paths.$path keys match",
                    );

                    foreach ($expectedData as $key => $expectedValue) {
                        switch ($expectedValue) {
                            case '__EXPECT_STRING__':
                                static::assertIsString(
                                    $actualData[$key],
                                    "artifacts.paths.$path.$key is a string",
                                );
                                break;

                            case '__EXPECT_INT__':
                                static::assertIsInt(
                                    $actualData[$key],
                                    "artifacts.paths.$path.$key is an integer",
                                );
                                break;

                            default:
                                static::assertSame(
                                    $expectedValue,
                                    $actualData[$key],
                                    "artifacts.paths.$path.$key match",
                                );
                                break;
                        }
                    }
                }
            }
        }
    }
}
