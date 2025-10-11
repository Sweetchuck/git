<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetConfigSingle;
use Sweetchuck\Git\OutcomeParser\ConfigParserBase;
use Sweetchuck\Git\OutcomeParser\GetConfigSingleParser;

#[CoversClass(GetConfigSingle::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetConfigSingleParser::class)]
#[CoversClass(ConfigParserBase::class)]
#[Group('command-git-config')]
class GetConfigSingleTest extends CommandTestBase
{
    protected string $gitRepoDir = '';

    protected function setUp(): void
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init --initial-branch="main" {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        parent::setUp();
        $this->gitRepoDir = $this->createTempDirectory();
        $this->executeSteps(
            $this->gitRepoDir,
            [
                $initStepGitInitCommon,
                [
                    'type' => 'exec',
                    'command' => 'cd {{ dirSafe }} && git config --local user.name "Test User"',
                ],
                [
                    'type' => 'exec',
                    'command' => 'cd {{ dirSafe }} && git config --local user.email "test@example.com"',
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
            ],
        );
    }

    #[Test]
    public function testGetSingleConfig(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigName('user.name');
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        $expected = [
            'scope' => 'local',
            'origin' => 'file:.git/config',
            'name' => 'user.name',
            'value.raw' => 'Test User',
            'value' => 'Test User',
        ];

        static::assertSame($expected, $result->artifacts);
    }

    #[Test]
    public function testGetBooleanConfig(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigName('core.fileMode');
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        $expected = [
            'scope' => 'local',
            'origin' => 'file:.git/config',
            'name' => 'core.filemode',
            'value.raw' => 'false',
            'value' => false,
        ];

        static::assertSame($expected, $result->artifacts);
    }

    #[Test]
    public function testGetMultipleValuesWithAllOption(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigName('foo.bar');
        $command->setAll(true);
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        $expected = [
            'foo.bar' => [
                [
                    'scope' => 'local',
                    'origin' => 'file:.git/config',
                    'name' => 'foo.bar',
                    'value.raw' => 'value1',
                    'value' => 'value1',
                ],
                [
                    'scope' => 'local',
                    'origin' => 'file:.git/config',
                    'name' => 'foo.bar',
                    'value.raw' => 'value2',
                    'value' => 'value2',
                ],
            ],
        ];

        static::assertSame($expected, $result->artifacts);
    }

    #[Test]
    public function testGetMultipleValuesWithoutAllOption(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigName('foo.bar');
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        $expected = [
            'scope' => 'local',
            'origin' => 'file:.git/config',
            'name' => 'foo.bar',
            'value.raw' => 'value2',
            'value' => 'value2',
        ];
        static::assertSame($expected, $result->artifacts);
    }

    #[Test]
    public function testGetConfigWithRegexp(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigScope([
            'local' => true,
            'global' => false,
            'system' => false,
        ]);
        $command->setConfigName('alias\..*');
        $command->setRegexp(true);
        $command->setAll(true);
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        $expected = [
            'alias.st' => [
                0 => [
                    'scope' => 'local',
                    'origin' => 'file:.git/config',
                    'name' => 'alias.st',
                    'value.raw' => 'status',
                    'value' => 'status',
                ],
            ],
            'alias.co' => [
                0 => [
                    'scope' => 'local',
                    'origin' => 'file:.git/config',
                    'name' => 'alias.co',
                    'value.raw' => 'checkout',
                    'value' => 'checkout',
                ],
            ],
        ];
        static::assertSame($expected, $result->artifacts);
    }

    #[Test]
    public function testGetConfigWithSpecificScope(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigName('user.name');
        $command->setConfigScope(['local' => true, 'global' => false, 'system' => false]);
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        static::assertIsArray($result->artifacts);
        static::assertArrayHasKey('scope', $result->artifacts);
        static::assertSame('local', $result->artifacts['scope']);
        static::assertSame('Test User', $result->artifacts['value']);
    }

    #[Test]
    public function testNonExistentConfig(): void
    {
        $command = new GetConfigSingle();
        $command->setConfigName('non.existent');
        $command->setWorkingDirectory($this->gitRepoDir);
        $result = $command->execute();

        static::assertNull($result->artifacts);
        static::assertSame(1, $result->process->getExitCode());
    }
}
