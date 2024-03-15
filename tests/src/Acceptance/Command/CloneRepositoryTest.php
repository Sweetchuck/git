<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\CloneRepository;
use Symfony\Component\Filesystem\Path;

/**
 * @phpstan-import-type SweetchuckGitCommandCloneRepositoryProperties from \Sweetchuck\Git\Phpstan
 */
#[CoversClass(CloneRepository::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-clone')]
class CloneRepositoryTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'properties' => [
                    'repository' => 'source-repo',
                    'directory' => 'target-repo',
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => implode(
                            ' && ',
                            [
                                'mkdir -p {{ dir }}/source-repo',
                                'cd {{ dir }}/source-repo',
                                'git init',
                                'echo "# Test Repository" > README.md',
                                'git add README.md',
                                'git config user.email "test@example.com"',
                                'git config user.name "Test User"',
                                'git commit -m "Initial commit"',
                            ],
                        ),
                    ],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isDir',
                        'path' => '{{ dir }}/target-repo/.git',
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/target-repo/README.md',
                    ],
                ],
            ],
            'with-bare-option' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'properties' => [
                    'repository' => 'source-repo',
                    'directory' => 'target-repo.git',
                    'bare' => true,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => implode(
                            ' && ',
                            [
                                'mkdir -p {{ dir }}/source-repo',
                                'cd {{ dir }}/source-repo',
                                'git init',
                                'echo "# Test Repository" > README.md',
                                'git add README.md',
                                'git config user.email "test@example.com"',
                                'git config user.name "Test User"',
                                'git commit -m "Initial commit"',
                            ],
                        ),
                    ],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isDir',
                        'path' => '{{ dir }}/target-repo.git',
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/target-repo.git/HEAD',
                    ],
                    [
                        'type' => 'isFile',
                        'expected' => false,
                        'path' => '{{ dir }}/target-repo.git/README.md',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<mixed> $initSteps
     * @param array<mixed> $verificationSteps
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

        // Execute initialization steps
        $this->executeSteps($projectDir, $initSteps);

        // Adjust paths in properties to be relative to the project directory
        if (isset($properties['repository'])) {
            $properties['repository'] = Path::join($projectDir, $properties['repository']);
        }

        if (isset($properties['directory'])) {
            $properties['directory'] = Path::join($projectDir, $properties['directory']);
        }

        if (isset($properties['template'])) {
            $properties['template'] = Path::join($projectDir, $properties['template']);
        }

        if (isset($properties['separateGitDir'])) {
            $properties['separateGitDir'] = Path::join($projectDir, $properties['separateGitDir']);
        }

        $command = new CloneRepository();
        $command->setProperties($properties);
        $result = $command->execute();

        if (isset($expected['artifacts'])) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
