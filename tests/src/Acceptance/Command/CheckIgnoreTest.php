<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CheckIgnore;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\OutcomeParser\CheckIgnoreParser;

#[CoversClass(CheckIgnore::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(CheckIgnoreParser::class)]
#[Group('command-git-check-ignore')]
class CheckIgnoreTest extends CommandTestBase
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

        $initStepGitIgnoreCommon = [
            'type' => 'createFile',
            'path' => '{{ dir }}/.gitignore',
            'content' => <<<'TEXT'
                *.md

                TEXT,
        ];

        return [
            'basic with non-matching' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'matching' => [
                            'ignore-me.md' => [
                                [
                                    'filePath' => '.gitignore',
                                    'lineNumber' => 1,
                                    'pattern' => '*.md',
                                ],
                            ],
                        ],
                        'nonMatching' => [
                            'track-me.txt',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    $initStepGitIgnoreCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/track-me.txt',
                        'content' => 'dummy',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add track-me.txt \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/ignore-me.md',
                        'content' => 'dummy',
                    ],
                ],
                'properties' => [
                    'paths' => [
                        'track-me.txt',
                        'ignore-me.md',
                    ],
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
        ?array $expected,
        array $initSteps,
        array $properties = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new CheckIgnore();
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
