<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\InitRepository;

#[CoversClass(InitRepository::class)]
#[Group('command-git-init')]
class InitRepositoryTest extends CommandTestBase
{
    protected function createCommand(): InitRepository
    {
        return new InitRepository();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'init'],
                'properties' => [],
            ],
            'with directory' => [
                'expected' => ['git', 'init', '/path/to/project-01'],
                'properties' => [
                    'directory' => '/path/to/project-01',
                ],
            ],
            'with bare option' => [
                'expected' => ['git', 'init', '--bare'],
                'properties' => [
                    'bare' => true,
                ],
            ],
            'with template option' => [
                'expected' => ['git', 'init', '--template=/path/to/template'],
                'properties' => [
                    'template' => '/path/to/template',
                ],
            ],
            'with separate-git-dir option' => [
                'expected' => ['git', 'init', '--separate-git-dir=/path/to/git/dir'],
                'properties' => [
                    'separateGitDir' => '/path/to/git/dir',
                ],
            ],
            'with object-format option' => [
                'expected' => ['git', 'init', '--object-format=sha256'],
                'properties' => [
                    'objectFormat' => 'sha256',
                ],
            ],
            'with ref-format option' => [
                'expected' => ['git', 'init', '--ref-format=reftable'],
                'properties' => [
                    'refFormat' => 'reftable',
                ],
            ],
            'with initial-branch option' => [
                'expected' => ['git', 'init', '--initial-branch=main'],
                'properties' => [
                    'initialBranch' => 'main',
                ],
            ],
            'with shared option as true' => [
                'expected' => ['git', 'init', '--shared'],
                'properties' => [
                    'shared' => true,
                ],
            ],
            'with shared option as false' => [
                'expected' => ['git', 'init', '--no-shared'],
                'properties' => [
                    'shared' => false,
                ],
            ],
            'with shared option as string' => [
                'expected' => ['git', 'init', '--shared=group'],
                'properties' => [
                    'shared' => 'group',
                ],
            ],
            'with multiple options' => [
                'expected' => [
                    'git',
                    'init',
                    '--bare',
                    '--template=/path/to/template',
                    '--separate-git-dir=/path/to/git/dir',
                    '--initial-branch=main',
                    '--shared=all',
                    '/path/to/project-01',
                ],
                'properties' => [
                    'bare' => true,
                    'template' => '/path/to/template',
                    'separateGitDir' => '/path/to/git/dir',
                    'initialBranch' => 'main',
                    'shared' => 'all',
                    'directory' => '/path/to/project-01',
                ],
            ],
            'with git executable and global options' => [
                'expected' => [
                    '/usr/local/bin/git',
                    'init',
                    '--bare',
                    '/path/to/project-01',
                ],
                'properties' => [
                    'gitExecutable' => '/usr/local/bin/git',
                    'bare' => true,
                    'directory' => '/path/to/project-01',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic - working copy' => [
                'expected' => [
                    'artifacts' => [
                        'gitDir' => '/path/to/project-01/.git/',
                    ],
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "Initialized empty Git repository in /path/to/project-01/.git/\n",
                    ],
                ],
                'properties' => [
                    'directory' => '/path/to/project-01',
                ],
            ],
            'basic - bare' => [
                'expected' => [
                    'artifacts' => [
                        'gitDir' => '/path/to/project-01/',
                    ],
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "Initialized empty Git repository in /path/to/project-01/\n",
                    ],
                ],
                'properties' => [
                    'bare' => true,
                    'directory' => '/path/to/project-01',
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $processOutcomes
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(array $expected, array $processOutcomes, array $properties): void
    {
        $processFactory = $this->createProcessFactory($processOutcomes);
        $result = $this
            ->createCommand()
            ->setProcessFactory($processFactory)
            ->setProperties($properties)
            ->execute();

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame(
                $expected['artifacts'],
                $result->artifacts,
            );
        }
    }

    #[Test]
    public function testGetSetDirectory(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getDirectory());

        $command->setDirectory('/path/to/project-01');
        static::assertSame('/path/to/project-01', $command->getDirectory());

        $command->setDirectory(null);
        static::assertNull($command->getDirectory());
    }

    #[Test]
    public function testGetSetBare(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getBare());

        $command->setBare(true);
        static::assertTrue($command->getBare());

        $command->setBare(false);
        static::assertFalse($command->getBare());

        $command->setBare(null);
        static::assertNull($command->getBare());
    }

    #[Test]
    public function testGetSetTemplate(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getTemplate());

        $command->setTemplate('/path/to/template');
        static::assertSame('/path/to/template', $command->getTemplate());

        $command->setTemplate(null);
        static::assertNull($command->getTemplate());
    }

    #[Test]
    public function testGetSetSeparateGitDir(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getSeparateGitDir());

        $command->setSeparateGitDir('/path/to/git/dir');
        static::assertSame('/path/to/git/dir', $command->getSeparateGitDir());

        $command->setSeparateGitDir(null);
        static::assertNull($command->getSeparateGitDir());
    }

    #[Test]
    public function testGetSetObjectFormat(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getObjectFormat());

        $command->setObjectFormat('sha256');
        static::assertSame('sha256', $command->getObjectFormat());

        $command->setObjectFormat(null);
        static::assertNull($command->getObjectFormat());
    }

    #[Test]
    public function testGetSetRefFormat(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getRefFormat());

        $command->setRefFormat('reftable');
        static::assertSame('reftable', $command->getRefFormat());

        $command->setRefFormat(null);
        static::assertNull($command->getRefFormat());
    }

    #[Test]
    public function testGetSetInitialBranch(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getInitialBranch());

        $command->setInitialBranch('main');
        static::assertSame('main', $command->getInitialBranch());

        $command->setInitialBranch(null);
        static::assertNull($command->getInitialBranch());
    }

    #[Test]
    public function testGetSetShared(): void
    {
        $command = $this->createCommand();

        static::assertNull($command->getShared());

        $command->setShared(true);
        static::assertTrue($command->getShared());

        $command->setShared('group');
        static::assertSame('group', $command->getShared());

        $command->setShared(null);
        static::assertNull($command->getShared());
    }

    #[Test]
    public function testSetProperties(): void
    {
        $command = $this->createCommand();

        $properties = [
            'directory' => '/path/to/project-01',
            'bare' => true,
            'template' => '/path/to/template',
            'separateGitDir' => '/path/to/git/dir',
            'objectFormat' => 'sha256',
            'refFormat' => 'reftable',
            'initialBranch' => 'main',
            'shared' => 'group',
        ];

        $command->setProperties($properties);

        static::assertSame('/path/to/project-01', $command->getDirectory());
        static::assertTrue($command->getBare());
        static::assertSame('/path/to/template', $command->getTemplate());
        static::assertSame('/path/to/git/dir', $command->getSeparateGitDir());
        static::assertSame('sha256', $command->getObjectFormat());
        static::assertSame('reftable', $command->getRefFormat());
        static::assertSame('main', $command->getInitialBranch());
        static::assertSame('group', $command->getShared());
    }
}
