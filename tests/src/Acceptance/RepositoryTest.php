<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\Command\InitRepository;
use Sweetchuck\Git\CommandFactory;
use Sweetchuck\Git\Repository;

#[CoversClass(Repository::class)]
class RepositoryTest extends TestBase
{
    public function testWorkflow01(): void
    {
        $this->expectNotToPerformAssertions();
        $dir = $this->createTempDirectory();
        $this->fs->touch("{$dir}/README.md");
        $git = $this->createRepository($dir);
        $git->stageFiles([
            'paths' => ['README.md'],
        ]);
        $git->commitStagedFiles([
            'message' => 'Initial commit',
        ]);
    }

    protected function createRepository(string $dir): Repository
    {
        $initCommand = new InitRepository();
        $initCommand->setDirectory($dir);
        $initCommand->execute();

        return new Repository($dir, new CommandFactory());
    }
}
