<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\GetFilesInTree;
use Sweetchuck\Git\Tests\Helper\DummyUniqueIdGenerator;

#[CoversClass(GetFilesInTree::class)]
#[Group('command-git-ls-tree')]
class GetFilesInTreeTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        $uniqueIdGenerator = new DummyUniqueIdGenerator();
        $command = new GetFilesInTree();
        $command->getFormatHandler()->setUniqueIdGenerator($uniqueIdGenerator);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        $expectedFormatDefault = '--format=';
        $expectedFormatDefault .= implode(
            '',
            [
                'path=%(path)',
                '¤objectMode=%(objectmode)',
                '¤objectType=%(objecttype)',
                '¤objectName=%(objectname)',
                '¤objectSize=%(objectsize)',
            ],
        );

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'ls-tree',
                    '-z',
                    $expectedFormatDefault,
                    'feature-42',
                ],
                'properties' => [
                    'treeish' => 'feature-42'
                ],
            ],
            'basic with paths' => [
                'expected' => [
                    'git',
                    'ls-tree',
                    '-z',
                    $expectedFormatDefault,
                    'feature-42',
                    '--',
                    'a.php',
                    'b.php',
                ],
                'properties' => [
                    'treeish' => 'feature-42',
                    'paths' => [
                        'a.php',
                        'b.php',
                    ],
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
                    'artifacts' => [
                        'paths' => [
                            'a.php' => [
                                'objectMode' => '100644',
                                'objectName' => 'name01',
                                'objectSize' => 21,
                                'objectType' => 'blob',
                                'path' => 'a.php',
                            ],
                            'b.php' => [
                                'objectMode' => '100644',
                                'objectName' => 'name02',
                                'objectSize' => 42,
                                'objectType' => 'blob',
                                'path' => 'b.php',
                            ],
                            'src' => [
                                'objectMode' => '100755',
                                'objectName' => 'name03',
                                'objectSize' => null,
                                'objectType' => 'tree',
                                'path' => 'src',
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
                                'path=a.php',
                                '¤objectMode=100644',
                                '¤objectType=blob',
                                '¤objectName=name01',
                                '¤objectSize=21',
                                "\0",
                                'path=b.php',
                                '¤objectMode=100644',
                                '¤objectType=blob',
                                '¤objectName=name02',
                                '¤objectSize=42',
                                "\0",
                                'path=src',
                                '¤objectMode=100755',
                                '¤objectType=tree',
                                '¤objectName=name03',
                                '¤objectSize=-',
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
