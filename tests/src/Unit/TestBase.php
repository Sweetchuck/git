<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Path;

class TestBase extends TestCase
{

    protected static function selfRoot(): string
    {
        return dirname(__DIR__, 3);
    }

    protected static function fixturesDir(string ...$parts): string
    {
        return Path::join(
            static::selfRoot(),
            'tests',
            'fixtures',
            ...$parts,
        );
    }

    /**
     * @param string[] $lines
     *
     * @return resource
     */
    protected function getFileHandler(array $lines)
    {
        $handler = fopen($this->getFileName($lines), 'r');
        if (!$handler) {
            throw new \RuntimeException();
        }

        return $handler;
    }

    /**
     * @param string[] $lines
     */
    protected function getFileName(array $lines): string
    {
        return 'data://text/plain;base64,' . base64_encode(implode(PHP_EOL, $lines));
    }
}
