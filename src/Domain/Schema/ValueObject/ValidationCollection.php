<?php

namespace Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, string>
 */
class ValidationCollection implements Countable, IteratorAggregate
{
    /**
     * @param  array<int, string>  $validations
     */
    public function __construct(
        private readonly array $validations
    ) {}

    /**
     * Get all validations as array.
     *
     * @return array<int, string>
     */
    public function toArray(): array
    {
        return $this->validations;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->validations);
    }

    public function count(): int
    {
        return count($this->validations);
    }

    /**
     * Create collection from array.
     *
     * @param  array<int, string>  $validations
     */
    public static function fromArray(array $validations): self
    {
        return new self($validations);
    }
}
