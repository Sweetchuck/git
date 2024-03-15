<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\RenameRemote;

#[CoversClass(RenameRemote::class)]
#[Group('command-git-remote')]
class RenameRemoteTest extends CommandTestBase
{
    protected function createCommand(): RenameRemote
    {
        return new RenameRemote();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'remote', 'rename', '--no-progress', 'origin', 'upstream'],
                'properties' => [
                    'oldName' => 'origin',
                    'newName' => 'upstream',
                ],
            ],
            'with-git-dir' => [
                'expected' => ['git', '--git-dir=/a/b/.git', 'remote', 'rename', '--no-progress', 'origin', 'upstream'],
                'properties' => [
                    'oldName' => 'origin',
                    'newName' => 'upstream',
                    'gitDir' => '/a/b/.git',
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
                    [],
                ],
                'properties' => [
                    'oldName' => 'origin',
                    'newName' => 'upstream',
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
