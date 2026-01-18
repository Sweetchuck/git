<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\PushRefs;
use Sweetchuck\Git\PushResult;

#[CoversClass(PushRefs::class)]
#[Group('command-git-push')]
class PushRefsTest extends CommandTestBase
{

    protected function createCommand(): PushRefs
    {
        return new PushRefs();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                ],
                'properties' => [],
            ],
            'basic-repo-and-refs' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-true' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--all',
                    '--prune',
                    '--mirror',
                    '--dry-run',
                    '--delete',
                    '--tags',
                    '--follow-tags',
                    '--signed',
                    '--atomic',
                    '--force-with-lease',
                    '--force-if-includes',
                    '--force',
                    '--set-upstream',
                    '--thin',
                    '--recurse-submodules',
                    '--verify',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'all' => true,
                    'prune' => true,
                    'mirror' => true,
                    'dryRun' => true,
                    'delete' => true,
                    'tags' => true,
                    'followTags' => true,
                    'signed' => true,
                    'atomic' => true,
                    'forceWithLease' => true,
                    'forceIfIncludes' => true,
                    'force' => true,
                    'setUpstream' => true,
                    'thin' => true,
                    'recurseSubmodules' => [true],
                    'verify' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-false' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--no-all',
                    '--no-prune',
                    '--no-mirror',
                    '--no-dry-run',
                    '--no-tags',
                    '--no-signed',
                    '--no-atomic',
                    '--no-force-with-lease',
                    '--no-force-if-includes',
                    '--no-force',
                    '--no-set-upstream',
                    '--no-thin',
                    '--no-recurse-submodules',
                    '--no-verify',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'all' => false,
                    'prune' => false,
                    'mirror' => false,
                    'dryRun' => false,
                    'tags' => false,
                    'signed' => false,
                    'atomic' => false,
                    'forceWithLease' => false,
                    'forceIfIncludes' => false,
                    'force' => false,
                    'setUpstream' => false,
                    'thin' => false,
                    'recurseSubmodules' => [false],
                    'verify' => false,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-string' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--signed=if-asked',
                    '--push-option=foo=bar',
                    '--receive-pack=/usr/bin/git-receive-pack',
                    '--exec=/usr/bin/git-receive-pack',
                    '--force-with-lease=myRef01',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'signed' => 'if-asked',
                    'pushOption' => [
                        'foo=bar' => true,
                    ],
                    'receivePack' => '/usr/bin/git-receive-pack',
                    'exec' => '/usr/bin/git-receive-pack',
                    'forceWithLease' => 'myRef01',
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'ipv4' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--ipv4',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'ipv' => '4',
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'ipv6' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--ipv6',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'ipv' => '6',
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'recurse-submodules-check' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--recurse-submodules=check',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'recurseSubmodules' => ['check'],
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'push-options-multiple' => [
                'expected' => [
                    'git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--push-option=key1=value1',
                    '--push-option=key2',
                    '--push-option=key3=value2',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'pushOption' => [
                        'key1=value1' => true,
                        'key2' => true,
                        'key3=value2' => true,
                    ],
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'global-options-single' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/.git',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--prune',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'gitDir' => '/path/to/.git',
                    'prune' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'global-options-multiple' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/.git',
                    '--work-tree=/path/to/worktree',
                    '-C', '/path/to/repo',
                    'push',
                    '--porcelain',
                    '--verbose',
                    '--prune',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'cwd' => '/path/to/repo',
                    'gitDir' => '/path/to/.git',
                    'workTree' => '/path/to/worktree',
                    'prune' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
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
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'refs/heads/1.x:refs/heads/1.x' => [
                            'result' => PushResult::UpToDate,
                            'refNameLocal' => 'refs/heads/1.x',
                            'refNameRemote' => 'refs/heads/1.x',
                            'message' => 'up to date',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode(
                            "\n",
                            [
                                "=\trefs/heads/1.x:refs/heads/1.x\t[up to date]",
                            ],
                        ),
                        'stdError' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<array<string, int|string>> $processOutcomes
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
