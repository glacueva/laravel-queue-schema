<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Tests\Mothers;

use Faker\Factory as FakerFactory;
use Glacueva\LaravelQueueSchema\Domain\Schema\Schema;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\ConsumerCollection;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\SchemaRule;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\SchemaRulesCollection;

class SchemaMother
{
    public static function create(
        ?string $id = null,
        ?string $publisher = null,
        ?array $consumers = null,
        ?string $version = null,
        ?SchemaRulesCollection $rules = null,
    ): Schema {
        $faker = FakerFactory::create();

        return new Schema(
            id: $id ?? $faker->uuid(),
            publisher: $publisher ?? $faker->lexify('????.service'),
            consumers: ConsumerCollection::fromArray($consumers ?? [$faker->lexify('????.service'), $faker->lexify('????.service')]),
            version: $version ?? '1.0.0',
            rules: $rules ?? self::defaultRulesCollection(),
        );
    }

    public static function withRules(array $rulesData): Schema
    {
        $rules = array_map(function (array $rule) {
            return SchemaRule::fromArray([
                'field' => $rule['field'],
                'validation' => $rule['validation'],
            ]);
        }, $rulesData);

        return self::create(rules: new SchemaRulesCollection($rules));
    }

    public static function defaultRulesCollection(): SchemaRulesCollection
    {
        return SchemaRulesCollection::fromArray([
            [
                'field' => 'email',
                'validation' => ['required', 'email'],
            ],
            [
                'field' => 'user_id',
                'validation' => ['required', 'integer'],
            ],
            [
                'field' => 'updated_at',
                'validation' => ['required', 'date'],
            ],
        ]);
    }

    public static function toArray(): array
    {
        $schema = self::create();

        return [
            'id' => $schema->id,
            'publisher' => $schema->publisher,
            'consumers' => $schema->consumers->toArray(),
            'version' => $schema->version,
            'rules' => $schema->rules->toArrayOfArrays(),
        ];
    }
}
