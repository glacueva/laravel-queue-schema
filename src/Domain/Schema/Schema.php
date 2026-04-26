<?php

namespace Glacueva\LaravelQueueSchema\Domain\Schema;

use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\ConsumerCollection;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\SchemaRulesCollection;

class Schema
{
    public function __construct(
        public readonly string $id,
        public readonly string $publisher,
        public readonly ConsumerCollection $consumers,
        public readonly string $version,
        public readonly SchemaRulesCollection $rules
    ) {}

    /**
     * Convert to array representation.
     *
     * @return array{publisherId: string, consumerId: array<int, string>, version: string, rules: array<int, array{fieldId: string, rules: array<int, string>}>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'publisher' => $this->publisher,
            'consumers' => $this->consumers->toArray(),
            'version' => $this->version,
            'rules' => $this->rules->toArrayOfArrays(),
        ];
    }

    /**
     * Create from array.
     *
     * @param  array{publisher: string, consumers: array<int, string>, version: string, rules: array<int, array{field: string, rules: array<int, string>}>}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            publisher: $data['publisher'],
            consumers: ConsumerCollection::fromArray($data['consumers']),
            version: $data['version'],
            rules: SchemaRulesCollection::fromArray($data['rules'])
        );
    }

    /**
     * Get validation rules in Laravel format.
     *
     * @return array<string, array<int, string>>
     */
    public function getValidationRules(): array
    {
        $rules = [];

        foreach ($this->rules as $schemaRule) {
            $fieldRules = [];

            $rules[$schemaRule->field] = $schemaRule->validation->toArray();
        }

        return $rules;
    }
}
