<?php

use Glacueva\LaravelQueueSchema\Domain\Schema\Exception\SchemaNotFoundException;
use Glacueva\LaravelQueueSchema\Domain\Schema\Schema;
use Glacueva\LaravelQueueSchema\Infrastructure\Schema\Models\QueueSchema as QueueSchemaModel;
use Glacueva\LaravelQueueSchema\Infrastructure\Schema\Persistence\EloquentSchemaRepository;
use Glacueva\LaravelQueueSchema\Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->schemaData = [
        'id' => '550e8400-e29b-41d4-a716-446655440000',
        'publisher' => 'user-service',
        'consumers' => ['email-service', 'analytics-service'],
        'version' => '1.0.0',
        'rules' => [
            ['field' => 'user_id', 'validation' => ['required', 'integer']],
        ],
    ];

    QueueSchemaModel::create($this->schemaData);

    $this->repository = new EloquentSchemaRepository;
});

describe('Retrieving Schemas (Happy Path)', function (): void {
    it('can retrieve a schema by its exact ID', function (): void {
        $result = $this->repository->getById('550e8400-e29b-41d4-a716-446655440000');

        expect($result)->toBeInstanceOf(Schema::class)
            ->and($result->id)->toBe('550e8400-e29b-41d4-a716-446655440000');
    });

    it('can retrieve a schema by its publisher', function (): void {
        $result = $this->repository->getByPublisher('user-service');

        expect($result)->toBeInstanceOf(Schema::class)
            ->and($result->publisher)->toBe('user-service');
    });

    it('can retrieve a schema by a consumer', function (): void {
        $result = $this->repository->getByConsumer('email-service');

        // Asegúrate de que $result->consumers sea una instancia de colección o similar
        expect($result)->toBeInstanceOf(Schema::class)
            ->and($result->consumers)->toContain('email-service');
    });

    it('can retrieve all registered schemas', function (): void {
        $results = $this->repository->all();

        expect($results)->toBeArray()
            ->and($results)->toHaveCount(1)
            ->and($results[0])->toBeInstanceOf(Schema::class);
    });
});

describe('Missing Data (Exception Handling)', function (): void {
    it('throws SchemaNotFoundException when the ID does not exist', function (): void {
        expect(fn () => $this->repository->getById('non-existent-id'))
            ->toThrow(SchemaNotFoundException::class);
    });

    it('throws SchemaNotFoundException when the publisher does not exist', function (): void {
        expect(fn () => $this->repository->getByPublisher('unknown-service'))
            ->toThrow(SchemaNotFoundException::class);
    });

    it('throws SchemaNotFoundException when the consumer does not exist', function (): void {
        expect(fn () => $this->repository->getByConsumer('unknown-service'))
            ->toThrow(SchemaNotFoundException::class);
    });
});
