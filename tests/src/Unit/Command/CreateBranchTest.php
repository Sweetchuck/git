<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CreateBranch;

/**
 * @phpstan-import-type SweetchuckGitCommandCreateBranchProperties from \Sweetchuck\Git\Phpstan
 */
#[CoversClass(CreateBranch::class)]
#[Group('command-git-branch')]
class CreateBranchTest extends CommandTestBase
{
    protected function createCommand(): CreateBranch
    {
        return new CreateBranch();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic - branch name only' => [
                'expected' => [
                    'git',
                    'branch',
                    'my-new-branch',
                ],
                'properties' => [
                    'name' => 'my-new-branch',
                ],
            ],
            'branch with start point' => [
                'expected' => [
                    'git',
                    'branch',
                    'feature-branch',
                    'main',
                ],
                'properties' => [
                    'name' => 'feature-branch',
                    'startPoint' => 'main',
                ],
            ],
            'branch with force' => [
                'expected' => [
                    'git',
                    'branch',
                    '--force',
                    'my-branch',
                ],
                'properties' => [
                    'name' => 'my-branch',
                    'force' => true,
                ],
            ],
            'all options combined' => [
                'expected' => [
                    'git',
                    'branch',
                    '--force',
                    'feature-branch',
                    'develop',
                ],
                'properties' => [
                    'name' => 'feature-branch',
                    'startPoint' => 'develop',
                    'force' => true,
                ],
            ],
            'global option - gitDir' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/project/.git',
                    'branch',
                    'new-branch',
                ],
                'properties' => [
                    'gitDir' => '/path/to/project/.git',
                    'name' => 'new-branch',
                ],
            ],
            'global option - workTree' => [
                'expected' => [
                    'git',
                    '--work-tree=/path/to/project',
                    'branch',
                    'new-branch',
                ],
                'properties' => [
                    'workTree' => '/path/to/project',
                    'name' => 'new-branch',
                ],
            ],
        ];
    }

    #[Test]
    public function testSetProperties(): void
    {
        $command = $this->createCommand();
        $properties = [
            'name' => 'test-branch',
            'startPoint' => 'main',
            'force' => true,
        ];

        $command->setProperties($properties);

        static::assertSame('test-branch', $command->getBranchName());
        static::assertSame('main', $command->getStartPoint());
        static::assertTrue($command->getForce());
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
                    'name' => 'new-branch',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'with start point' => [
                'expected' => [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                    'artifacts' => null,
                ],
                'properties' => [
                    'name' => 'feature-branch',
                    'startPoint' => 'develop',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'error - branch already exists' => [
                'expected' => [
                    'exitCode' => 128,
                    'stdOutput' => '',
                    'stdError' => "fatal: A branch named 'existing-branch' already exists.",
                    'artifacts' => null,
                ],
                'properties' => [
                    'name' => 'existing-branch',
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
