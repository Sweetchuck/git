<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\PruneRemote;

#[CoversClass(PruneRemote::class)]
#[Group('command-git-remote')]
class PruneRemoteTest extends CommandTestBase
{

    protected function createCommand(): PruneRemote
    {
        return new PruneRemote();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'remote', 'prune', 'origin'],
                'properties' => [
                    'name' => 'origin',
                ],
            ],
            'with-dry-run' => [
                'expected' => ['git', 'remote', 'prune', '--dry-run', 'origin'],
                'properties' => [
                    'name' => 'origin',
                    'dryRun' => true,
                ],
            ],
            'with-git-dir' => [
                'expected' => ['git', '--git-dir=/path/to/repo/.git', 'remote', 'prune', 'origin'],
                'properties' => [
                    'name' => 'origin',
                    'gitDir' => '/path/to/repo/.git',
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
                    'name' => 'upstream',
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
