<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader;

class ReaderFactory
{
    const NULL_HASH = '0000';

    /**
     * @var array<string, class-string<\Sweetchuck\Git\StdInputReader\BaseReader<int, \Sweetchuck\Git\StdInputReader\Item\BaseItem>>>
     */
    public static array $classNameMapping = [
        'post-receive' => PostReceiveReader::class,
        'post-rewrite' => PostRewriteReader::class,
        'pre-push' => PrePushReader::class,
        'pre-receive' => PreReceiveReader::class,
    ];

    /**
     * @param string $gitHook
     * @param resource $fileHandler
     *
     * @return \Sweetchuck\Git\StdInputReader\BaseReader<int, \Sweetchuck\Git\StdInputReader\Item\BaseItem>
     *
     * @throws \InvalidArgumentException
     */
    public static function createInstance(string $gitHook, $fileHandler): ?BaseReader
    {
        $className = static::$classNameMapping[$gitHook] ?? null;

        return $className ? new $className($fileHandler) : null;
    }
}
