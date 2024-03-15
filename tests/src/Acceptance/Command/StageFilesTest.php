<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\StageFiles;

#[CoversClass(StageFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-add')]
class StageFilesTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic-single-file' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                ],
                'properties' => [
                    'paths' => ['README.md'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'A  README.md',
                    ],
                ],
            ],
            'multiple-files' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
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
                ],
                'properties' => [
                    'paths' => ['file1.txt', 'file2.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file1.txt"',
                        'expectedOutput' => 'A  file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file2.txt"',
                        'expectedOutput' => 'A  file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file3.txt"',
                        'expectedOutput' => '?? file3.txt',
                    ],
                ],
            ],
            'all-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/existing.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add existing.txt && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/existing.txt',
                        'content' => 'Modified content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/new.txt',
                        'content' => 'New file content',
                    ],
                ],
                'properties' => [
                    'all' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "existing.txt"',
                        'expectedOutput' => 'M  existing.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "new.txt"',
                        'expectedOutput' => 'A  new.txt',
                    ],
                ],
            ],
            'dry-run-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/test.txt',
                        'content' => 'Test content',
                    ],
                ],
                'properties' => [
                    'dryRun' => true,
                    'paths' => ['test.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '?? test.txt',
                    ],
                ],
            ],
            'intent-to-add-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/intent.txt',
                        'content' => 'Intent to add content',
                    ],
                ],
                'properties' => [
                    'intentToAdd' => true,
                    'paths' => ['intent.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'A intent.txt',
                    ],
                ],
            ],
            'update-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/tracked.txt',
                        'content' => 'Original tracked content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add tracked.txt && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/tracked.txt',
                        'content' => 'Modified tracked content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/untracked.txt',
                        'content' => 'New untracked content',
                    ],
                ],
                'properties' => [
                    'update' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "tracked.txt"',
                        'expectedOutput' => 'M  tracked.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "untracked.txt"',
                        'expectedOutput' => '?? untracked.txt',
                    ],
                ],
            ],
            'force-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitignore',
                        'content' => 'ignored.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .gitignore && git commit -m "Add gitignore"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/ignored.txt',
                        'content' => 'This file should be ignored',
                    ],
                ],
                'properties' => [
                    'force' => true,
                    'paths' => ['ignored.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "ignored.txt"',
                        'expectedOutput' => 'A  ignored.txt',
                    ],
                ],
            ],
            'chmod-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/script.sh',
                        'content' => '#!/bin/bash\necho "Hello World"',
                    ],
                ],
                'properties' => [
                    'chmod' => '+x',
                    'paths' => ['script.sh'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'AM script.sh',
                    ],
                ],
            ],
            'subdirectory-files' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'mkdir -p {{ dirSafe }}/src/lib',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/main.php',
                        'content' => '<?php echo "Hello";',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/lib/helper.php',
                        'content' => '<?php function help() {}',
                    ],
                ],
                'properties' => [
                    'paths' => ['src/'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "src/main.php"',
                        'expectedOutput' => 'A  src/main.php',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "src/lib/helper.php"',
                        'expectedOutput' => 'A  src/lib/helper.php',
                    ],
                ],
            ],
            'ignore-removal-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'File 1 content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'File 2 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add . && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'Modified file 1 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'rm {{ dirSafe }}/file2.txt',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file3.txt',
                        'content' => 'New file 3 content',
                    ],
                ],
                'properties' => [
                    'ignoreRemoval' => true,
                    'all' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file1.txt"',
                        'expectedOutput' => 'M file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file2.txt"',
                        'expectedOutput' => 'D file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file3.txt"',
                        'expectedOutput' => '?? file3.txt',
                    ],
                ],
            ],
            'empty-repository' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                ],
                'properties' => [
                    'all' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'refresh-option' => [
                'expected' => [
                    'exitCode' => 128,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/refresh.txt',
                        'content' => 'Refresh test content',
                    ],
                ],
                'properties' => [
                    'refresh' => true,
                    'paths' => ['refresh.txt'],
                ],
                'verificationSteps' => [],
            ],
            'ignore-errors-option' => [
                'expected' => [
                    'exitCode' => 128,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/valid.txt',
                        'content' => 'Valid file content',
                    ],
                ],
                'properties' => [
                    'ignoreErrors' => true,
                    'paths' => ['valid.txt', 'nonexistent.txt'],
                ],
                'verificationSteps' => [],
            ],
            'ignore-missing-option' => [
                'expected' => [
                    'exitCode' => 128,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/existing.txt',
                        'content' => 'Existing file content',
                    ],
                ],
                'properties' => [
                    'ignoreMissing' => true,
                    'paths' => ['existing.txt', 'missing.txt'],
                ],
                'verificationSteps' => [],
            ],
            'combined-options' => [
                'expected' => [
                    'exitCode' => 128,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/combo.txt',
                        'content' => 'Combined options test',
                    ],
                ],
                'properties' => [
                    'refresh' => true,
                    'ignoreErrors' => true,
                    'ignoreMissing' => true,
                    'paths' => ['combo.txt'],
                ],
                'verificationSteps' => [],
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

        $command = new StageFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
