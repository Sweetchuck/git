<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetConfigMultiple;
use Sweetchuck\Git\OutcomeParser\ConfigParserBase;
use Sweetchuck\Git\OutcomeParser\GetConfigMultipleParser;

#[CoversClass(GetConfigMultiple::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetConfigMultipleParser::class)]
#[CoversClass(ConfigParserBase::class)]
#[Group('command-git-config')]
class GetConfigMultipleTest extends CommandTestBase
{
    protected string $gitRepoDir = '';

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initSteps = [
            [
                'type' => 'exec',
                'command' => <<<'SHELL'
                    git init --initial-branch="main" {{ dirSafe }} \
                    && cd {{ dirSafe }} \
                    && git config --local user.email 'test@example.com' \
                    && git config --local user.name  'Test User'
                    SHELL,
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local core.fileMode false',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local alias.st status',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local alias.co checkout',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --add --local foo.bar "value1"',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --add --local foo.bar "value2"',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local section1.key1 "value1"',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local section1.key2 "value2"',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local section2.key1 "true"',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git config --local section2.key2 "false"',
            ],
        ];

        $properties = [
            'configScope' => [
                'global' => false,
                'system' => false,
                'local' => true,
            ],
        ];

        return [
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'core.repositoryformatversion' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'core.repositoryformatversion',
                            'value.raw' => '0',
                            'value' => '0',
                        ],
                        'core.filemode' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'core.filemode',
                            'value.raw' => 'false',
                            'value' => false,
                        ],
                        'core.bare' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'core.bare',
                            'value.raw' => 'false',
                            'value' => false,
                        ],
                        'core.logallrefupdates' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'core.logallrefupdates',
                            'value.raw' => 'true',
                            'value' => true,
                        ],
                        'user.email' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'user.email',
                            'value.raw' => 'test@example.com',
                            'value' => 'test@example.com',
                        ],
                        'user.name' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'user.name',
                            'value.raw' => 'Test User',
                            'value' => 'Test User',
                        ],
                        'alias.st' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'alias.st',
                            'value.raw' => 'status',
                            'value' => 'status',
                        ],
                        'alias.co' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'alias.co',
                            'value.raw' => 'checkout',
                            'value' => 'checkout',
                        ],
                        'foo.bar' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'foo.bar',
                            'value.raw' => 'value2',
                            'value' => 'value2',
                        ],
                        'section1.key1' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'section1.key1',
                            'value.raw' => 'value1',
                            'value' => 'value1',
                        ],
                        'section1.key2' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'section1.key2',
                            'value.raw' => 'value2',
                            'value' => 'value2',
                        ],
                        'section2.key1' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'section2.key1',
                            'value.raw' => 'true',
                            'value' => true,
                        ],
                        'section2.key2' => [
                            'scope' => 'local',
                            'origin' => 'file:.git/config',
                            'name' => 'section2.key2',
                            'value.raw' => 'false',
                            'value' => false,
                        ],
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => $properties,
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

        $command = new GetConfigMultiple();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
