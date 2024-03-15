<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetStatus;

#[CoversClass(GetStatus::class)]
class GetStatusTest extends CommandTestBase
{

    protected function createCommand(): GetStatus
    {
        return new GetStatus();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                ['git', 'status', '--porcelain', '-z'],
                [],
            ],

            'renames null' => [
                ['git', 'status', '--porcelain', '-z'],
                [
                    'renames' => null,
                ],
            ],
            'renames true' => [
                ['git', 'status', '--porcelain', '-z', '--renames'],
                [
                    'renames' => true,
                ],
            ],
            'renames false' => [
                ['git', 'status', '--porcelain', '-z', '--no-renames'],
                [
                    'renames' => false,
                ],
            ],

            'findRenames null' => [
                ['git', 'status', '--porcelain', '-z'],
                [
                    'findRenames' => null,
                ],
            ],
            'findRenames 0' => [
                ['git', 'status', '--porcelain', '-z', '--find-renames'],
                [
                    'findRenames' => 0,
                ],
            ],
            'findRenames 1' => [
                ['git', 'status', '--porcelain', '-z', '--find-renames=1'],
                [
                    'findRenames' => 1,
                ],
            ],

            'ignored null' => [
                ['git', 'status', '--porcelain', '-z'],
                [
                    'ignored' => null,
                ],
            ],
            'ignored empty string' => [
                ['git', 'status', '--porcelain', '-z', '--ignored'],
                [
                    'ignored' => '',
                ],
            ],
            'ignored traditional' => [
                ['git', 'status', '--porcelain', '-z', '--ignored=traditional'],
                [
                    'ignored' => 'traditional',
                ],
            ],

            'untracked-files null' => [
                ['git', 'status', '--porcelain', '-z'],
                [
                    'untrackedFiles' => null,
                ],
            ],
            'untracked-files empty string' => [
                ['git', 'status', '--porcelain', '-z', '--untracked-files'],
                [
                    'untrackedFiles' => '',
                ],
            ],
            'untracked-files value' => [
                ['git', 'status', '--porcelain', '-z', '--untracked-files=value'],
                [
                    'untrackedFiles' => 'value',
                ],
            ],

            'paths' => [
                ['git', 'status', '--porcelain', '-z', '--', '*.yml'],
                [
                    'paths' => [
                        '*.yml' => true,
                    ],
                ],
            ],

            'all-in-one' => [
                [
                    'git',
                    'status',
                    '--porcelain',
                    '-z',
                    '--renames',
                    '--find-renames=1',
                    '--ignored=a',
                    '--untracked-files=b',
                    '--',
                    '*.yml',
                ],
                [
                    'renames' => true,
                    'findRenames' => 1,
                    'ignored' => 'a',
                    'untrackedFiles' => 'b',
                    'paths' => [
                        '*.yml' => true,
                        '*.php' => false,
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
            'basic - empty' => [
                'expected' => [
                    'artifacts' => [],
                ],
                'options' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => "\n",
                    ],
                ],
            ],
            'basic - not empty' => [
                'expected' => [
                    'artifacts' => [
                        'a.txt' => ' M',
                        'b.txt' => 'MM',
                        'c.txt' => 'D ',
                    ],
                ],
                'options' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode(
                            "\0",
                            [
                                ' M a.txt',
                                'MM b.txt',
                                'D  c.txt',
                            ],
                        ),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $options
     * @param array<array<string, mixed>> $processOutcomes
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(array $expected, array $options, array $processOutcomes = []): void
    {
        if (!array_key_exists('processFactory', $options)) {
            $options['processFactory'] = $this->createProcessFactory($processOutcomes);
        }
        $command = new GetStatus();
        $command->setOptions($options);

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
