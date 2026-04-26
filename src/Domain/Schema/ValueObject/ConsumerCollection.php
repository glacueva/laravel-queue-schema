<?php

namespace Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, string>
 */
class ConsumerCollection implements Countable, IteratorAggregate
{
    /**
     * @param  array<int, string>  $consumers
     */
    public function __construct(
        private readonly array $consumers
    ) {}

    /**
     * Get all consumers as array.
     *
     * @return array<int, string>
     */
    public function toArray(): array
    {
        return $this->consumers;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->consumers);
    }

    public function count(): int
    {
        return count($this->consumers);
    }

    /**
     * Create collection from array.
     *
     * @param  array<int, string>  $consumers
     */
    public static function fromArray(array $consumers): self
    {
        return new self($consumers);
    }
}
