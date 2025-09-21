<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\UpsertSymbolicRef;

#[CoversClass(UpsertSymbolicRef::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-symbolic-ref')]
class UpsertSymbolicRefTest extends CommandTestBase
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
            'create-basic-symbolic-ref' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [
                    'name' => 'refs/heads/current-branch',
                    'pointsTo' => 'refs/heads/main',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git symbolic-ref refs/heads/current-branch',
                        'expectedOutput' => 'refs/heads/main',
                    ],
                ],
            ],
            'create-symbolic-ref-with-message' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [
                    'message' => 'Creating feature branch alias',
                    'name' => 'refs/heads/feature-branch',
                    'pointsTo' => 'refs/heads/main',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git symbolic-ref --short refs/heads/feature-branch',
                        'expectedOutput' => 'main',
                    ],
                ],
            ],
            'update-existing-symbolic-ref' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git checkout -b development \
                            && git checkout main \
                            && git symbolic-ref refs/heads/current refs/heads/main
                            SHELL,
                    ],
                ],
                'properties' => [
                    'name' => 'refs/heads/current',
                    'pointsTo' => 'refs/heads/development',
                    'message' => 'Updating current branch to point to development',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git symbolic-ref --short refs/heads/current',
                        'expectedOutput' => 'development',
                    ],
                ],
            ],
            'create-symbolic-ref-to-tag' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.0.0',
                    ],
                ],
                'properties' => [
                    'name' => 'refs/heads/stable',
                    'pointsTo' => 'refs/tags/v1.0.0',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git symbolic-ref refs/heads/stable',
                        'expectedOutput' => 'refs/tags/v1.0.0',
                    ],
                ],
            ],
            'create-nested-symbolic-ref' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git symbolic-ref refs/heads/alias-1 refs/heads/main
                            SHELL,
                    ],
                ],
                'properties' => [
                    'name' => 'refs/heads/alias-2',
                    'pointsTo' => 'refs/heads/alias-1',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git symbolic-ref --no-recurse refs/heads/alias-2',
                        'expectedOutput' => 'refs/heads/alias-1',
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

        $command = new UpsertSymbolicRef();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
