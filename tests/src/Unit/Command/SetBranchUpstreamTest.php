<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SetBranchUpstream;

/**
 * @phpstan-import-type SweetchuckGitCommandSetBranchUpstreamProperties from \Sweetchuck\Git\Phpstan
 */
#[CoversClass(SetBranchUpstream::class)]
#[Group('command-git-branch')]
class SetBranchUpstreamTest extends CommandTestBase
{
    protected function createCommand(): SetBranchUpstream
    {
        return new SetBranchUpstream();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic - set upstream for current branch' => [
                'expected' => [
                    'git',
                    'branch',
                    '--set-upstream-to=origin/main',
                ],
                'properties' => [
                    'upstream' => 'origin/main',
                ],
            ],
            'set upstream for specific branch' => [
                'expected' => [
                    'git',
                    'branch',
                    '--set-upstream-to=origin/develop',
                    'feature-branch',
                ],
                'properties' => [
                    'upstream' => 'origin/develop',
                    'branch' => 'feature-branch',
                ],
            ],
            'with different remote' => [
                'expected' => [
                    'git',
                    'branch',
                    '--set-upstream-to=upstream/main',
                    'local-branch',
                ],
                'properties' => [
                    'upstream' => 'upstream/main',
                    'branch' => 'local-branch',
                ],
            ],
            'global option - gitDir' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/project/.git',
                    'branch',
                    '--set-upstream-to=origin/main',
                ],
                'properties' => [
                    'gitDir' => '/path/to/project/.git',
                    'upstream' => 'origin/main',
                ],
            ],
            'global option - workTree' => [
                'expected' => [
                    'git',
                    '--work-tree=/path/to/project',
                    'branch',
                    '--set-upstream-to=origin/main',
                    'my-branch',
                ],
                'properties' => [
                    'workTree' => '/path/to/project',
                    'upstream' => 'origin/main',
                    'branch' => 'my-branch',
                ],
            ],
        ];
    }

    #[Test]
    public function testSetProperties(): void
    {
        $command = $this->createCommand();
        $properties = [
            'upstream' => 'origin/main',
            'branch' => 'feature-branch',
        ];

        $command->setProperties($properties);

        static::assertSame('origin/main', $command->getUpstream());
        static::assertSame('feature-branch', $command->getBranch());
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
                    'upstream' => 'origin/main',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'error - invalid upstream' => [
                'expected' => [
                    'exitCode' => 128,
                    'stdOutput' => '',
                    'stdError' => "fatal: the requested upstream branch 'invalid/branch' does not exist",
                    'artifacts' => null,
                ],
                'properties' => [
                    'upstream' => 'invalid/branch',
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 128,
                        'stdOutput' => '',
                        'stdError' => "fatal: the requested upstream branch 'invalid/branch' does not exist",
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
