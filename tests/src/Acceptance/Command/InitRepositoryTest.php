<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\InitRepository;
use Symfony\Component\Filesystem\Path;

#[CoversClass(InitRepository::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-init')]
class InitRepositoryTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'gitDir' => 'my-project/.git/',
                    ],
                ],
                'properties' => [
                    'directory' => 'my-project'
                ],
                'initSteps' => [],
                'verificationSteps' => [
                    [
                        'type' => 'isDir',
                        'path' => '{{ dir }}/my-project/.git',
                    ],
                ],
            ],
            'with-bare-option' => [
                'expected' => [
                    'artifacts' => [
                        'gitDir' => 'my-project.git/',
                    ],
                ],
                'properties' => [
                    'bare' => true,
                    'directory' => 'my-project.git'
                ],
                'initSteps' => [],
                'verificationSteps' => [
                    [
                        'type' => 'isDir',
                        'path' => '{{ dir }}/my-project.git',
                    ],
                ],
            ],
            'with-template-option' => [
                'expected' => [
                    'artifacts' => [
                        'gitDir' => 'my-project-01/.git/',
                    ],
                ],
                'properties' => [
                    'template' => 'template-dir',
                    'directory' => 'my-project-01'
                ],
                'initSteps' => [
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/template-dir/my-stuff/zzz.txt',
                        'content' => 'dummy',
                    ],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/my-project-01/.git/my-stuff/zzz.txt',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<mixed> $verificationSteps
     * @param array<mixed> $initSteps
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $properties = [],
        array $initSteps = [],
        array $verificationSteps = [],
    ): void {
        $projectDir = $this->createTempDirectory();

        // Adjust paths in properties to be relative to the project directory
        $properties['directory'] = isset($properties['directory'])
            ? Path::join($projectDir, $properties['directory'])
            : $projectDir;

        if (isset($properties['template'])) {
            $properties['template'] = Path::join($projectDir, $properties['template']);
        }

        if (isset($properties['separateGitDir'])) {
            $properties['separateGitDir'] = Path::join($projectDir, $properties['separateGitDir']);
        }

        $this->executeSteps($projectDir, $initSteps);

        $command = new InitRepository();
        $command->setProperties($properties);
        $result = $command->execute();

        if (isset($expected['artifacts']['gitDir'])) {
            $expectedGitDir = Path::join($projectDir, $expected['artifacts']['gitDir']);
            $actualGitDir = $result->artifacts['gitDir'];

            $expectedGitDir = rtrim($expectedGitDir, '/\\') . '/';
            $actualGitDir = rtrim($actualGitDir, '/\\') . '/';

            static::assertSame($expectedGitDir, $actualGitDir);
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
