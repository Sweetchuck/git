<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\StdInputReader;

use PHPUnit\Framework\Attributes\DataProvider;
use Sweetchuck\Git\StdInputReader\BaseReader;
use Sweetchuck\Git\StdInputReader\ReaderFactory;
use Sweetchuck\Git\Tests\Unit\TestBase;

abstract class ReaderTestBase extends TestBase
{

    protected string $gitHook = '';

    /**
     * @param string $gitHook
     * @param string[] $lines
     *
     * @return null|\Sweetchuck\Git\StdInputReader\BaseReader<int, \Sweetchuck\Git\StdInputReader\Item\BaseItem>
     */
    protected function getReader(string $gitHook, array $lines): ?BaseReader
    {
        return ReaderFactory::createInstance($gitHook, $this->getFileHandler($lines));
    }

    /**
     * @return array<string, mixed>
     */
    abstract public static function caseAllInOne(): array;

    /**
     * @param string[] $lines
     */
    #[DataProvider('caseAllInOne')]
    public function testAllInOne(array $lines): void
    {
        $reader = $this->getReader($this->gitHook, $lines);

        $reader->seek(2);
        static::assertSame($lines[2], (string) $reader->current());
        static::assertSame(2, $reader->key());

        $reader->seek(1);
        static::assertSame($lines[1], (string) $reader->current());

        $reader->rewind();
        static::assertSame($lines[0], (string) $reader->current());

        $reader->seek(3);
        static::assertSame(count($lines), count($reader));
        static::assertSame(
            $lines[3],
            (string) $reader->current(),
            "After count() the position hasn't changed."
        );

        foreach ($reader as $key => $item) {
            static::assertSame($lines[$key], (string) $item);
        }
        static::assertSame(count($lines), count($reader));
    }
}
