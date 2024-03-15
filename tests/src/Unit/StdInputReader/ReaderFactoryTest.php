<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\StdInputReader;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Sweetchuck\Git\StdInputReader\BaseReader;
use Sweetchuck\Git\StdInputReader\PostReceiveReader;
use Sweetchuck\Git\StdInputReader\PostRewriteReader;
use Sweetchuck\Git\StdInputReader\PrePushReader;
use Sweetchuck\Git\StdInputReader\PreReceiveReader;
use Sweetchuck\Git\StdInputReader\ReaderFactory;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(ReaderFactory::class)]
class ReaderFactoryTest extends TestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesCreateInstance(): array
    {
        return [
            'post-receive 01' => [
                PostReceiveReader::class,
                'post-receive',
            ],
            'post-rewrite 01' => [
                PostRewriteReader::class,
                'post-rewrite',
            ],
            'pre-push 01' => [
                PrePushReader::class,
                'pre-push',
            ],
            'pre-receive 01' => [
                PreReceiveReader::class,
                'pre-receive',
            ],
        ];
    }

    #[DataProvider('casesCreateInstance')]
    public function testCreateInstance(string $expected, string $gitHook): void
    {
        $reader = $this->getReader($gitHook, []);
        static::assertSame($expected, get_class($reader));
    }

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
}
