<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetFileContent;
use Sweetchuck\Git\OutcomeParser\FileContentParser;

#[CoversClass(GetFileContent::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(FileContentParser::class)]
#[Group('command-git-show')]
class GetFileContentTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic-file-content' => [
                'expected' => [
                    'artifacts' => [
                        'content' => "Hello, World!\n",
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/test-file.txt',
                        'content' => "Hello, World!\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add test-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add test file"',
                    ],
                ],
                'properties' => [
                    'filePath' => 'test-file.txt',
                    'commitHash' => 'HEAD',
                ],
            ],
            'staged-file-content' => [
                'expected' => [
                    'artifacts' => [
                        'content' => "Staged content\n",
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/staged-file.txt',
                        'content' => "Staged content\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add staged-file.txt',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/staged-file.txt',
                        'content' => "Working copy content\n",
                    ],
                ],
                'properties' => [
                    'filePath' => 'staged-file.txt',
                ],
            ],
            'non-existent-file' => [
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
                        'command' => 'cd {{ dirSafe }} && git commit --allow-empty -m "Empty commit"',
                    ],
                ],
                'properties' => [
                    'filePath' => 'non-existent-file.txt',
                    'commitHash' => 'HEAD',
                ],
            ],
            'invalid-commit-hash' => [
                'expected' => [
                    'exitCode' => 128,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/test-file.txt',
                        'content' => "Hello, World!\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add test-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add test file"',
                    ],
                ],
                'properties' => [
                    'filePath' => 'test-file.txt',
                    'commitHash' => 'invalid-commit-hash',
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

        $command = new GetFileContent();
        $command->setProperties($properties);

        if (!empty($expected['expectedException'])) {
            $this->expectException($expected['expectedException']);
            $command->execute();

            return;
        }

        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
