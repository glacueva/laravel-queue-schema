<?php

namespace Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, SchemaRule>
 */
class SchemaRulesCollection implements Countable, IteratorAggregate
{
    /**
     * @param  array<int, SchemaRule>  $rules
     */
    public function __construct(
        private readonly array $rules
    ) {}

    /**
     * Get all rules as array.
     *
     * @return array<int, SchemaRule>
     */
    public function toArray(): array
    {
        return $this->rules;
    }

    /**
     * Convert to array of arrays.
     *
     * @return array<int, array{fieldId: string, rules: array<int, string>}>
     */
    public function toArrayOfArrays(): array
    {
        return array_map(fn (SchemaRule $rule) => $rule->toArray(), $this->rules);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->rules);
    }

    public function count(): int
    {
        return count($this->rules);
    }

    /**
     * Create collection from array.
     *
     * @param  array<int, array{fieldId: string, rules: array<int, string>}>  $rules
     */
    public static function fromArray(array $rules): self
    {
        $schemaRules = array_map(
            fn (array $rule) => SchemaRule::fromArray($rule),
            $rules
        );

        return new self($schemaRules);
    }
}
