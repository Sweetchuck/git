<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader;

use Countable;
use Iterator;
use SeekableIterator;
use Sweetchuck\Git\StdInputReader\Item\BaseItem;

/**
 * @template TKey int
 * @template TValue \Sweetchuck\Git\StdInputReader\Item\BaseItem
 *
 * @implements \Iterator<TValue>
 * @implements \SeekableIterator<TKey, TValue>
 */
abstract class BaseReader implements Iterator, SeekableIterator, Countable
{

    /**
     * @var array<int, \Sweetchuck\Git\StdInputReader\Item\BaseItem>
     */
    protected array $items = [];

    protected int $currentIndex = -1;

    /**
     * @var resource
     */
    protected $fileHandler;

    /**
     * @param resource $fileHandler
     */
    public function __construct($fileHandler)
    {
        $this->fileHandler = $fileHandler;
        $this->readNext();
    }

    /**
     * {@inheritdoc}
     */
    public function current(): ?BaseItem
    {
        return $this->items[$this->currentIndex] ?? null;
    }

    abstract protected function parse(string $line): BaseItem;

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->readNext();
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->currentIndex;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return isset($this->items[$this->currentIndex]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->currentIndex = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset): void
    {
        if (isset($this->items[$offset])) {
            $this->currentIndex = $offset;

            return;
        }

        if (!$this->isAllRead()) {
            $this->currentIndex = count($this->items) - 1;
        }

        while ($this->valid() && $this->currentIndex < $offset) {
            $this->next();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        $this->readAll();

        return count($this->items);
    }

    protected function readNext(): void
    {
        $this->currentIndex++;
        if (!$this->isAllRead() && !$this->valid()) {
            $line = fgets($this->fileHandler);
            if (!$line) {
                return;
            }

            $this->items[$this->currentIndex] = $this->parse($line);
        }
    }

    protected function readAll(): static
    {
        if ($this->isAllRead()) {
            return $this;
        }

        $currentIndex = $this->currentIndex;
        while ($this->valid()) {
            $this->next();
        }

        $this->seek($currentIndex);

        return $this;
    }

    protected function isAllRead(): bool
    {
        return feof($this->fileHandler);
    }
}
