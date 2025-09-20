<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetRemoteFetchUrls;
use Sweetchuck\Git\OutcomeParser\LinesParser;

#[CoversClass(GetRemoteFetchUrls::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(LinesParser::class)]
#[Group('command-git-remote')]
class GetRemoteFetchUrlsTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'remote-name-not-exists' => [
                'expected' => [
                    'exitCode' => 2,
                    'artifacts' => [
                        'urls' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [
                    'remoteName' => 'not-exists',
                ],
            ],
            'single-remote' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'urls' => [
                            '/dev/null/fetch-01.git',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git remote add            upstream /dev/null/fetch-01.git \
                            && git remote set-url --push upstream /dev/null/push-01.git
                            SHELL,
                    ],
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                ],
            ],
            'multiple-remote' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'urls' => [
                            '/dev/null/fetch-01.git',
                            '/dev/null/fetch-02.git',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git remote add            upstream /dev/null/fetch-01.git \
                            && git remote set-url --push upstream /dev/null/push-01.git \
                            && git remote set-url --add  upstream /dev/null/fetch-02.git
                            SHELL,
                    ],
                ],
                'properties' => [
                    'remoteName' => 'upstream',
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

        $command = new GetRemoteFetchUrls();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
