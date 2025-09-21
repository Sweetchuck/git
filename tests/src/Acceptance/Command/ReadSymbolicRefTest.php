<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\ReadSymbolicRef;

#[CoversClass(ReadSymbolicRef::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-symbolic-ref')]
class ReadSymbolicRefTest extends CommandTestBase
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
                && git config user.name  'Test User' \
                && touch README.md \
                && git add README.md \
                && git commit --message 'Initial commit'
                SHELL,
        ];

        return [
            'read-head-ref' => [
                'expected' => [
                    'artifacts' => [
                        'name.full' => 'refs/heads/main',
                        'name.short' => 'main',
                        'type' => 'heads',
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [
                    'name' => 'HEAD',
                ],
            ],
            'read-feature-branch-ref' => [
                'expected' => [
                    'artifacts' => [
                        'name.full' => 'refs/heads/feature/new-feature',
                        'name.short' => 'feature/new-feature',
                        'type' => 'heads',
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout -b feature/new-feature',
                    ],
                ],
                'properties' => [
                    'name' => 'HEAD',
                ],
            ],
            'read-symbolic-ref-alias' => [
                'expected' => [
                    'artifacts' => [
                        'name.full' => 'refs/heads/main',
                        'name.short' => 'main',
                        'type' => 'heads',
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git symbolic-ref refs/heads/current-branch refs/heads/main',
                    ],
                ],
                'properties' => [
                    'name' => 'refs/heads/current-branch',
                ],
            ],
            'non-existent-ref' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                ],
                'properties' => [
                    'name' => 'refs/heads/non-existent',
                ],
            ],
            'multi-level-recurse-true' => [
                'expected' => [
                    'artifacts' => [
                        'name.full' => 'refs/heads/main',
                        'name.short' => 'main',
                        'type' => 'heads',
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git symbolic-ref refs/heads/alias-1 refs/heads/main \
                            && git symbolic-ref refs/heads/alias-2 refs/heads/alias-1
                            SHELL,
                    ],
                ],
                'properties' => [
                    'recurse' => true,
                    'name' => 'refs/heads/alias-2',
                ],
            ],
            'multi-level-recurse-false' => [
                'expected' => [
                    'artifacts' => [
                        'name.full' => 'refs/heads/alias-1',
                        'name.short' => 'alias-1',
                        'type' => 'heads',
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git symbolic-ref refs/heads/alias-1 refs/heads/main \
                            && git symbolic-ref refs/heads/alias-2 refs/heads/alias-1
                            SHELL,
                    ],
                ],
                'properties' => [
                    'recurse' => false,
                    'name' => 'refs/heads/alias-2',
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

        $command = new ReadSymbolicRef();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
