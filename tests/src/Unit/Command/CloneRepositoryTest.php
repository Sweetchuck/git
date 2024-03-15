<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CloneRepository;

#[CoversClass(CloneRepository::class)]
#[Group('command-git-clone')]
class CloneRepositoryTest extends CommandTestBase
{
    protected function createCommand(): CloneRepository
    {
        return new CloneRepository();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'clone',
                    '--no-progress',
                    'https://github.com/sweetchuck/git.git',
                ],
                'properties' => [
                    'repository' => 'https://github.com/sweetchuck/git.git',
                ],
            ],
            'basic with directory' => [
                'expected' => [
                    'git',
                    'clone',
                    '--no-progress',
                    'https://github.com/sweetchuck/git.git',
                    '/path/to/project-01',
                ],
                'properties' => [
                    'repository' => 'https://github.com/sweetchuck/git.git',
                    'directory' => '/path/to/project-01',
                ],
            ],
            'all in one - true' => [
                'expected' => [
                    'git',
                    'clone',
                    '--no-progress',
                    '--config=key-01=value-12',
                    '--config=key-02=value-13',
                    '--reject-shallow',
                    '--checkout',
                    '--bare',
                    '--mirror',
                    '--local',
                    '--hardlinks',
                    '--shared',
                    '--recurse-submodules',
                    '--jobs=4',
                    '--template=value-01',
                    '--reference=value-02',
                    '--reference-if-able=value-03',
                    '--dissociate',
                    '--origin=value-04',
                    '--branch=value-05',
                    '--revision=value-06',
                    '--upload-pack=value-07',
                    '--depth=3',
                    '--shallow-since=value-08',
                    '--shallow-exclude=value-09',
                    '--single-branch',
                    '--tags',
                    '--shallow-submodules',
                    '--separate-git-dir=value-10',
                    '--ref-format=value-11',
                    '--no-server-option',
                    '--server-option=value-14',
                    '--server-option=value-15',
                    '--ipv4',
                    '--no-filter',
                    '--filter=value-16',
                    '--filter=value-17',
                    '--also-filter-submodules',
                    '--sparse',
                    '--bundle-uri=value-18',
                    'arg-01',
                    'arg-02',
                ],
                'properties' => [
                    'rejectShallow' => true,
                    'checkout' => true,
                    'bare' => true,
                    'mirror' => true,
                    'local' => true,
                    'hardlinks' => true,
                    'shared' => true,
                    'jobs' => 4,
                    'template' => 'value-01',
                    'reference' => 'value-02',
                    'referenceIfAble' => 'value-03',
                    'dissociate' => true,
                    'origin' => 'value-04',
                    'branch' => 'value-05',
                    'revision' => 'value-06',
                    'uploadPack' => 'value-07',
                    'depth' => 3,
                    'shallowSince' => 'value-08',
                    'shallowExclude' => 'value-09',
                    'singleBranch' => true,
                    'tags' => true,
                    'shallowSubmodules' => true,
                    'separateGitDir' => 'value-10',
                    'refFormat' => 'value-11',
                    'config' => [
                        'key-01' => 'value-12',
                        'key-02' => 'value-13',
                    ],
                    'serverOption' => [
                        false,
                        'value-14',
                        'value-15',
                    ],
                    'ipv' => '4',
                    'filter' => [
                        false,
                        'value-16',
                        'value-17',
                    ],
                    'alsoFilterSubmodules' => true,
                    'sparse' => true,
                    'bundleUri' => 'value-18',
                    'repository' => 'arg-01',
                    'directory' => 'arg-02',
                    'recurseSubmodules' => [true],
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
            'basic' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "Cloning into '/path/to/project-01' ...\n",
                    ],
                ],
                'properties' => [
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
}
