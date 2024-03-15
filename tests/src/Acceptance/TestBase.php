<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class TestBase extends TestCase
{

    protected Filesystem $fs;

    /**
     * @var array<string>
     */
    protected array $tempDirs = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->fs = new Filesystem();
    }

    protected function createTempDirectory(): string
    {
        $namespaceParts = explode('\\', __NAMESPACE__);
        $tempDir = Path::join(
            $this->getTempDir(),
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

    /**
     * {@inheritdoc}
     */
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

    protected function getTempDir(): string
    {
        return \getenv('SWEETCHUCK_GIT_TESTS_TEMP_DIR') ?: \sys_get_temp_dir();
    }
}
