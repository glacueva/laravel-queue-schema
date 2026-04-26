<?php

namespace Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject;

class SchemaRule
{
    public function __construct(
        public readonly string $field,
        public readonly ValidationCollection $validation
    ) {}

    /**
     * Convert to array representation.
     *
     * @return array{field: string, validation: array<int, string>}
     */
    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'validation' => $this->validation->toArray(),
        ];
    }

    /**
     * Create from array.
     *
     * @param  array{field: string, validation: array<int, string>}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            field: $data['field'],
            validation: ValidationCollection::fromArray($data['validation'])
        );
    }
}
