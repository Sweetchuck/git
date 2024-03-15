<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SetBranchUpstream;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(SetBranchUpstream::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-branch')]
class SetBranchUpstreamTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'set-upstream-for-current-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --bare {{ dirSafe }}/remote.git',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="main" {{ dirSafe }}/working',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<< 'SHELL'
                            cd {{ dirSafe }}/working \
                            && git config user.email 'test@example.com' \
                            && git config user.name 'Test User' \
                            && touch README.md \
                            && git add README.md \
                            && git commit --message='Initial commit' \
                            && git remote add origin '../remote.git' \
                            && git push origin main
                            SHELL,
                    ],
                ],
                'properties' => [
                    'upstream' => 'origin/main',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/working && git config --get branch.main.remote',
                        'expectedOutput' => 'origin',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/working && git config --get branch.main.merge',
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
     * @param array<mixed> $verifySteps
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
        array $verifySteps = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = "$projectDir/working";
        $this->executeSteps($projectDir, $initSteps);

        $command = new SetBranchUpstream();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame($expected['exitCode'], $result->process->getExitCode());
        static::assertSame($expected['artifacts'], $result->artifacts);

        if (!empty($verifySteps)) {
            $this->executeSteps($projectDir, $verifySteps);
        }
    }
}
