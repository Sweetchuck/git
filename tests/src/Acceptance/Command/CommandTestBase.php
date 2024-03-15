<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use Sweetchuck\Git\Tests\Acceptance\TestBase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class CommandTestBase extends TestBase
{

    protected Filesystem $fs;

    /**
     * @var array<string>
     */
    protected array $tempDirs = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->fs = new Filesystem();
    }

    protected function createTempDirectory(): string
    {
        $namespaceParts = explode('\\', __NAMESPACE__);
        $tempDir = Path::join(
            sys_get_temp_dir(),
            implode('-', array_slice($namespaceParts, 0, 3)),
        );
        $this->fs->mkdir($tempDir);
        $tempDir = tempnam($tempDir, date('Ymd-His-'));
        if (!$tempDir) {
            throw new \RuntimeException(sprintf('Temporary directory "%s" could not be created.', $tempDir));
        }

        unlink($tempDir);
        if (!mkdir($tempDir) && !is_dir($tempDir)) {
            throw new \RuntimeException(sprintf('Temporary directory "%s" could not be created.', $tempDir));
        }

        $this->tempDirs[] = $tempDir;

        return $tempDir;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tearDownTempDirs();
    }

    protected function tearDownTempDirs(): static
    {
        $this->fs->remove($this->tempDirs);

        return $this;
    }

    /**
     * @param array<array<string, mixed>> $steps
     */
    protected function initGitRepo(string $dir, array $steps): static
    {
        $replacementPairs = [
            '{{ dirSafe }}' => escapeshellarg($dir),
        ];
        foreach ($steps as $step) {
            switch ($step['type']) {
                case 'exec':
                    $command = strtr($step['command'], $replacementPairs);
                    $exitCode = null;
                    exec($command, $output, $exitCode);
                    if ($exitCode !== 0) {
                        throw new \RuntimeException(sprintf('Command "%s" failed.', $command));
                    }
                    break;

                default:
                    // @todo Do something.
                    break;
            }
        }

        return $this;
    }
}
