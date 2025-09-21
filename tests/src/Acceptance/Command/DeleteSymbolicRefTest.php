<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\DeleteSymbolicRef;

#[CoversClass(DeleteSymbolicRef::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-symbolic-ref')]
class DeleteSymbolicRefTest extends CommandTestBase
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
            'delete-basic-symbolic-ref' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git symbolic-ref refs/heads/alias-01 refs/heads/main \
                            && git symbolic-ref refs/heads/alias-02 refs/heads/main
                            SHELL,
                    ],
                ],
                'properties' => [
                    'name' => 'refs/heads/alias-01',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} && \
                            git symbolic-ref refs/heads/alias-01
                            SHELL,
                        'expectedExitCode' => 128,
                        'expectedError' => 'fatal: ref refs/heads/alias-01 is not a symbolic ref',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} && \
                            git symbolic-ref refs/heads/alias-02
                            SHELL,
                        'expectedExitCode' => 0,
                        'expectedOutput' => 'refs/heads/main',
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

        $command = new DeleteSymbolicRef();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
