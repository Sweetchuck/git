<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\ReadSymbolicRef;

#[CoversClass(ReadSymbolicRef::class)]
#[Group('command-git-symbolic-ref')]
class ReadSymbolicRefTest extends CommandTestBase
{

    protected function createCommand(): ReadSymbolicRef
    {
        return new ReadSymbolicRef();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                [
                    'git',
                    'symbolic-ref',
                    'refs/heads/alias',
                ],
                [
                    'name' => 'refs/heads/alias',
                ],
            ],
            'recurse - false' => [
                [
                    'git',
                    'symbolic-ref',
                    '--no-recurse',
                    'refs/heads/alias',
                ],
                [
                    'recurse' => false,
                    'name' => 'refs/heads/alias',
                ],
            ],
            'recurse - true' => [
                [
                    'git',
                    'symbolic-ref',
                    '--recurse',
                    'refs/heads/alias',
                ],
                [
                    'recurse' => true,
                    'name' => 'refs/heads/alias',
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
            'basic exitCode 2' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 2,
                        'stdOutput' => "\n",
                    ],
                ],
            ],
            'basic empty' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'properties' => [],
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
                        'name.full' => 'refs/heads/feature/issue-42',
                        'name.short' => 'feature/issue-42',
                        'type' => 'heads',
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => "refs/heads/feature/issue-42\n",
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
    public function testExecute(?array $expected, array $properties, array $processOutcomes = []): void
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
