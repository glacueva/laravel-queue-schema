<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Tests\Unit\Domain;

use Glacueva\LaravelQueueSchema\Domain\Schema\Schema;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\ConsumerCollection;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\SchemaRulesCollection;
use Glacueva\LaravelQueueSchema\Tests\Mothers\SchemaMother;

describe('Schema', function (): void {
    describe('construction', function (): void {
        it('creates a schema with all required fields', function (): void {
            $schema = SchemaMother::create();

            expect($schema)->toBeInstanceOf(Schema::class)
                ->id->toBeString()
                ->publisher->toBeString()
                ->consumers->toBeInstanceOf(ConsumerCollection::class)
                ->version->toBeString()
                ->rules->toBeInstanceOf(SchemaRulesCollection::class);
        });
    });

    describe('fromArray', function (): void {
        it('correctly hydrates ConsumerCollection and SchemaRulesCollection', function (): void {
            $data = [
                'id' => 'test-id',
                'publisher' => 'test.publisher',
                'consumers' => ['consumer1', 'consumer2'],
                'version' => '2.0.0',
                'rules' => [
                    ['field' => 'email', 'validation' => ['required', 'email']],
                    ['field' => 'user_id', 'validation' => ['required', 'integer']],
                ],
            ];

            $schema = Schema::fromArray($data);

            expect($schema)
                ->id->toBe('test-id')
                ->publisher->toBe('test.publisher')
                ->consumers->toMatchArray(['consumer1', 'consumer2'])
                ->version->toBe('2.0.0');
            expect($schema->rules->toArray())->toHaveCount(2)
                ->{0}->field->toBe('email')
                ->{0}->validation->toMatchArray(['required', 'email'])
                ->{1}->field->toBe('user_id')
                ->{1}->validation->toMatchArray(['required', 'integer']);
        });
    });

    describe('getValidationRules', function (): void {
        it('returns flat array with field as key and validation rules as value', function (): void {
            $schema = SchemaMother::create();

            $rules = $schema->getValidationRules();

            expect($rules)->toBeArray();
            expect($rules)->toHaveKeys(['email', 'user_id', 'updated_at']);
            expect($rules['email'])->toMatchArray(['required', 'email']);
            expect($rules['user_id'])->toMatchArray(['required', 'integer']);
            expect($rules['updated_at'])->toMatchArray(['required', 'date']);
        });

        it('returns correct format for Laravel validation', function (): void {
            $rulesData = [
                ['field' => 'name', 'validation' => ['required', 'string', 'max:100']],
                ['field' => 'age', 'validation' => ['required', 'integer', 'min:18']],
            ];

            $schema = SchemaMother::withRules($rulesData);
            $rules = $schema->getValidationRules();

            expect($rules['name'])->toMatchArray(['required', 'string', 'max:100']);
            expect($rules['age'])->toMatchArray(['required', 'integer', 'min:18']);
        });
    });

    describe('toArray', function (): void {
        it('converts schema to array representation', function (): void {
            $schema = SchemaMother::create(
                id: 'test-id',
                publisher: 'test.pub',
                consumers: ['consumer1'],
                version: '1.5.0'
            );

            expect($schema->toArray())->toHaveKeys(['id', 'publisher', 'consumers', 'version', 'rules'])
                ->id->toBe('test-id')
                ->publisher->toBe('test.pub')
                ->consumers->toMatchArray(['consumer1'])
                ->version->toBe('1.5.0');
        });
    });
});
