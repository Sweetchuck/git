<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\GetFileContent;

#[CoversClass(GetFileContent::class)]
#[Group('command-git-show')]
class GetFileContentTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        return new GetFileContent();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'show', 'abc1234:path/to/file.txt'],
                'properties' => [
                    'commitHash' => 'abc1234',
                    'filePath' => 'path/to/file.txt',
                ],
            ],
            'with-git-dir' => [
                'expected' => ['git', '--git-dir=/path/to/repo/.git', 'show', 'abc1234:path/to/file.txt'],
                'properties' => [
                    'gitDir' => '/path/to/repo/.git',
                    'commitHash' => 'abc1234',
                    'filePath' => 'path/to/file.txt',
                ],
            ],
            'without-commit-hash' => [
                'expected' => ['git', 'show', ':path/to/file.txt'],
                'properties' => [
                    'filePath' => 'path/to/file.txt',
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesGetCliCommand')]
    public function testGetCliCommand(array $expected, array $properties): void
    {
        $command = new GetFileContent();
        $command->setProperties($properties);

        $this->assertSame($expected, $command->getCliCommand());
    }

    public function testSetterGetter(): void
    {
        $command = new GetFileContent();

        $this->assertNull($command->getCommitHash());
        $this->assertNull($command->getFilePath());

        $command->setCommitHash('abc1234');
        $this->assertSame('abc1234', $command->getCommitHash());

        $command->setFilePath('path/to/file.txt');
        $this->assertSame('path/to/file.txt', $command->getFilePath());
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
                        'content' => "Hello world!\n",
                    ],
                ],
                'properties' => [
                    'filePath' => 'path/to/file.txt',
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "Hello world!\n",
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
