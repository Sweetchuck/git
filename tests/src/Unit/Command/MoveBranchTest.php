<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\MoveBranch;

/**
 * @phpstan-import-type SweetchuckGitCommandMoveBranchProperties from \Sweetchuck\Git\Phpstan
 */
#[CoversClass(MoveBranch::class)]
#[Group('command-git-branch')]
class MoveBranchTest extends CommandTestBase
{
    protected function createCommand(): MoveBranch
    {
        return new MoveBranch();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic - rename branch' => [
                'expected' => [
                    'git',
                    'branch',
                    '--move',
                    'old-branch',
                    'new-branch',
                ],
                'properties' => [
                    'oldName' => 'old-branch',
                    'newName' => 'new-branch',
                ],
            ],
            'global option - gitDir' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/project/.git',
                    'branch',
                    '--move',
                    'feature',
                    'feature-renamed',
                ],
                'properties' => [
                    'gitDir' => '/path/to/project/.git',
                    'oldName' => 'feature',
                    'newName' => 'feature-renamed',
                ],
            ],
            'global option - workTree' => [
                'expected' => [
                    'git',
                    '--work-tree=/path/to/project',
                    'branch',
                    '--move',
                    'main',
                    'master',
                ],
                'properties' => [
                    'workTree' => '/path/to/project',
                    'oldName' => 'main',
                    'newName' => 'master',
                ],
            ],
        ];
    }

    #[Test]
    public function testSetProperties(): void
    {
        $command = $this->createCommand();
        $properties = [
            'oldName' => 'old-branch-name',
            'newName' => 'new-branch-name',
        ];

        $command->setProperties($properties);

        static::assertSame('old-branch-name', $command->getOldName());
        static::assertSame('new-branch-name', $command->getNewName());
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                    'artifacts' => null,
                ],
                'properties' => [
                    'oldName' => 'feature',
                    'newName' => 'feature-renamed',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'error - branch does not exist' => [
                'expected' => [
                    'exitCode' => 128,
                    'stdOutput' => '',
                    'stdError' => "fatal: branch 'non-existent' not found.",
                    'artifacts' => null,
                ],
                'properties' => [
                    'oldName' => 'non-existent',
                    'newName' => 'renamed',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 128,
                        'stdOutput' => '',
                        'stdError' => "fatal: branch 'non-existent' not found.",
                    ],
                ],
            ],
            'error - target branch already exists' => [
                'expected' => [
                    'exitCode' => 128,
                    'stdOutput' => '',
                    'stdError' => "fatal: A branch named 'existing-branch' already exists.",
                    'artifacts' => null,
                ],
                'properties' => [
                    'oldName' => 'source-branch',
                    'newName' => 'existing-branch',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 128,
                        'stdOutput' => '',
                        'stdError' => "fatal: A branch named 'existing-branch' already exists.",
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

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (array_key_exists('stdOutput', $expected)) {
            static::assertSame($expected['stdOutput'], $result->process->getOutput());
        }

        if (array_key_exists('stdError', $expected)) {
            static::assertSame($expected['stdError'], $result->process->getErrorOutput());
        }

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
