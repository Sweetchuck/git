<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\FetchRefs;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(FetchRefs::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-fetch')]
class FetchRefsTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
        'type' => 'exec',
        'command' => <<<'SHELL'
            git init --bare {{ dirSafe }}/upstream.git \
            && git init --initial-branch="main" {{ dirSafe }}/workspace \
            && cd {{ dirSafe }}/workspace \
            && git config user.email "test@example.com" \
            && git config user.name "Test User" \
            && git remote add upstream ../upstream.git
            SHELL,
        ];

        return [
            'fetch-single-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git add README.md \
                            && git commit -m "Initial commit"
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git switch --create='feature-1' \
                            && echo '<?php echo "Hello World!";' > index.php \
                            && git add index.php \
                            && git commit --message="Add feature-1" \
                            && git push upstream feature-1 --set-upstream \
                            && git reset 'HEAD^' --hard
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git --no-pager log -1 --format='%s'
                            SHELL,
                        'expectedExitCode' => 0,
                        'expectedOutput' => 'Initial commit',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git switch main
                            SHELL,
                        'expectedExitCode' => 0,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['feature-1:feature-1'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git --no-pager log -1 --format='%s' feature-1
                            SHELL,
                        'expectedExitCode' => 0,
                        'expectedOutput' => 'Add feature-1',
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
        $properties['workingDirectory'] = "$projectDir/workspace";
        $this->executeSteps($projectDir, $initSteps);

        $command = new FetchRefs();
        $command->setProperties($properties);
        $result = $command->execute();

        $stdOutput = $result->process->getOutput();
        $stdError = $result->process->getErrorOutput();
        static::assertSame(
            $expected['exitCode'],
            $result->process->getExitCode(),
            <<< TEXT
                --== stdOutout ==--
                {$stdOutput}

                --== stdError ==--
                {$stdError}
                TEXT,
        );
        static::assertSame($expected['artifacts'], $result->artifacts);

        $this->executeSteps($projectDir, $verifySteps);
    }
}
