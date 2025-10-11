<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetFilesInWorkingCopy;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Tests\Helper\DummyUniqueIdGenerator;

#[CoversClass(GetFilesInWorkingCopy::class)]
#[Group('command-git-ls-files')]
class GetFilesInWorkingCopyTest extends CommandTestBase
{

    protected function createCommand(): GetFilesInWorkingCopy
    {
        $uniqueIdGenerator = new DummyUniqueIdGenerator();
        $command = new GetFilesInWorkingCopy();
        $command->getFormatHandler()->setUniqueIdGenerator($uniqueIdGenerator);

        return $command;
    }

    public static function casesGetCliCommand(): array
    {
        $expectedFormatDefault = '--format=';
        $expectedFormatDefault .= implode(
            '',
            [
                'objectName=%(objectname)',
                '¤objectMode=%(objectmode)',
                '¤objectType=%(objecttype)',
                '¤stage=%(stage)',
                '¤eolInfoIndex=%(eolinfo:index)',
                '¤eolInfoWorkTree=%(eolinfo:worktree)',
                '¤eolAttributes=%(eolattr)',
                '¤objectSize=%(objectsize)',
                '¤path=%(path)',
            ],
        );

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'ls-files',
                    '-z',
                    $expectedFormatDefault,
                ],
                'properties' => [],
            ],
            'all-in-one:true' => [
                'expected' => [
                    'git',
                    'ls-files',
                    '-z',
                    '--cached',
                    '--deleted',
                    '--modified',
                    '--ignored',
                    '--stage',
                    '--directory',
                    '--no-empty-directory',
                    '--unmerged',
                    '--killed',
                    '--resolve-undo',
                    '--exclude-standard',
                    '--error-unmatch',
                    '--sparse',
                    '--with-tree=my-tree-01',
                    $expectedFormatDefault,
                ],
                'properties' => [
                    'cached' => true,
                    'deleted' => true,
                    'modified' => true,
                    'ignored' => true,
                    'stage' => true,
                    'directory' => true,
                    'noEmptyDirectory' => true,
                    'unmerged' => true,
                    'killed' => true,
                    'resolveUndo' => true,
                    'excludeStandard' => true,
                    'errorUnmatch' => true,
                    'sparse' => true,
                    // exclude
                    // excludeFrom
                    // excludePerDirectory
                    'withTree' => 'my-tree-01',
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
            'empty' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => '',
                    ],
                ],
            ],
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'tracked.php' => [
                                'eolAttributes' => 'text eol=lf',
                                'eolInfoIndex' => 'lf',
                                'eolInfoWorkTree' => 'lf',
                                'objectMode' => '100644',
                                'objectName' => 'id01',
                                'objectSize' => 42,
                                'objectType' => 'blob',
                                'path' => 'tracked.php',
                                'stage' => '0',
                            ],
                            'untracked.php' => [
                                'eolAttributes' => 'text eol=lf',
                                'eolInfoIndex' => 'lf',
                                'eolInfoWorkTree' => 'lf',
                                'objectMode' => '100644',
                                'objectName' => 'id02',
                                'objectSize' => 43,
                                'objectType' => 'blob',
                                'path' => 'untracked.php',
                                'stage' => '0',
                            ],
                            'unmerged.php' => [
                                'eolAttributes' => 'text eol=lf',
                                'eolInfoIndex' => 'lf',
                                'eolInfoWorkTree' => 'lf',
                                'objectMode' => '100644',
                                'objectName' => 'id03',
                                'objectSize' => 44,
                                'objectType' => 'blob',
                                'path' => 'unmerged.php',
                                'stage' => '0',
                            ],
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                'objectName=id01',
                                '¤objectMode=100644',
                                '¤objectType=blob',
                                '¤stage=0',
                                '¤eolInfoIndex=lf',
                                '¤eolInfoWorkTree=lf',
                                '¤eolAttributes=text eol=lf',
                                '¤objectSize=42',
                                '¤path=tracked.php',
                                "\0",
                                'objectName=id02',
                                '¤objectMode=100644',
                                '¤objectType=blob',
                                '¤stage=0',
                                '¤eolInfoIndex=lf',
                                '¤eolInfoWorkTree=lf',
                                '¤eolAttributes=text eol=lf',
                                '¤objectSize=43',
                                '¤path=untracked.php',
                                "\0",
                                'objectName=id03',
                                '¤objectMode=100644',
                                '¤objectType=blob',
                                '¤stage=0',
                                '¤eolInfoIndex=lf',
                                '¤eolInfoWorkTree=lf',
                                '¤eolAttributes=text eol=lf',
                                '¤objectSize=44',
                                '¤path=unmerged.php',
                                "\0",
                            ],
                        ),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<array<string, mixed>> $processOutcomes
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(array $expected, array $properties, array $processOutcomes = []): void
    {
        if (!array_key_exists('processFactory', $properties)) {
            $properties['processFactory'] = $this->createProcessFactory($processOutcomes);
        }
        $command = $this->createCommand();
        $command->setProperties($properties);

        $result = $command->execute();

        if (isset($expected['exitCode'])) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (isset($expected['stdOutput'])) {
            static::assertSame($expected['stdOutput'], $result->process->getOutput());
        }

        if (isset($expected['stdError'])) {
            static::assertSame($expected['stdError'], $result->process->getErrorOutput());
        }

        if (isset($expected['artifacts'])) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
