<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\RemoveFiles;

#[CoversClass(RemoveFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-rm')]
class RemoveFilesTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'basic-single-file' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'paths' => ['README.md'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/README.md',
                        'expected' => false,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'D  README.md',
                    ],
                ],
            ],
            'multiple-files' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'Content 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'Content 2',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file3.txt',
                        'content' => 'Content 3',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add . && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'paths' => ['file1.txt', 'file2.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/file1.txt',
                        'expected' => false,
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/file2.txt',
                        'expected' => false,
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/file3.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'D  file1.txt',
                    ],
                ],
            ],
            'cached-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/cached.txt',
                        'content' => 'Cached content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add cached.txt && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'cached' => true,
                    'paths' => ['cached.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/cached.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'D  cached.txt',
                    ],
                ],
            ],
            'force-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add modified.txt && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/modified.txt',
                        'content' => 'Modified content without staging',
                    ],
                ],
                'properties' => [
                    'force' => true,
                    'paths' => ['modified.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/modified.txt',
                        'expected' => false,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'D  modified.txt',
                    ],
                ],
            ],
            'dry-run-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dryrun.txt',
                        'content' => 'Dry run content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add dryrun.txt && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'dryRun' => true,
                    'paths' => ['dryrun.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/dryrun.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'recursive-option-directory' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/subdir/file1.txt',
                        'content' => 'Subdirectory file 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/subdir/file2.txt',
                        'content' => 'Subdirectory file 2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add . && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'recursive' => true,
                    'paths' => ['subdir'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isDir',
                        'path' => '{{ dir }}/subdir',
                        'expected' => false,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "subdir/file1.txt"',
                        'expectedOutput' => 'D  subdir/file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "subdir/file2.txt"',
                        'expectedOutput' => 'D  subdir/file2.txt',
                    ],
                ],
            ],
            'ignore-unmatch-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/existing.txt',
                        'content' => 'Existing file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add existing.txt && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'ignoreUnmatch' => true,
                    'paths' => ['existing.txt', 'nonexistent.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/existing.txt',
                        'expected' => false,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'D  existing.txt',
                    ],
                ],
            ],
            'combined-options' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/combo.txt',
                        'content' => 'Combined options test',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add combo.txt && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'cached' => true,
                    'force' => true,
                    'ignoreUnmatch' => true,
                    'paths' => ['combo.txt', 'missing.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/combo.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'D  combo.txt',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     * @param array<mixed> $verificationSteps
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
        array $verificationSteps = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new RemoveFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
