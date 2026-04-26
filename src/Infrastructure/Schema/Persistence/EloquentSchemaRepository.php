<?php

namespace Glacueva\LaravelQueueSchema\Infrastructure\Schema\Persistence;

use Glacueva\LaravelQueueSchema\Domain\Schema\Exception\SchemaNotFoundException;
use Glacueva\LaravelQueueSchema\Domain\Schema\Schema;
use Glacueva\LaravelQueueSchema\Domain\Schema\SchemaRepository;
use Glacueva\LaravelQueueSchema\Infrastructure\Schema\Models\QueueSchema as QueueSchemaModel;

class EloquentSchemaRepository implements SchemaRepository
{
    public function getById(string $id): Schema
    {
        $queueSchema = QueueSchemaModel::find($id);

        throw_if(! $queueSchema, new SchemaNotFoundException("Schema not found for ID: {$id}"));

        return $this->mapModelToSchema($queueSchema);
    }

    public function getByPublisher(string $publisher): Schema
    {
        $queueSchema = QueueSchemaModel::where('publisher', $publisher)->first();

        throw_if(! $queueSchema, new SchemaNotFoundException("Schema not found for publisher ID: {$publisher}"));

        return $this->mapModelToSchema($queueSchema);
    }

    public function getByConsumer(string $consumer): Schema
    {
        $queueSchema = QueueSchemaModel::whereJsonContains('consumers', $consumer)->first();

        throw_if(! $queueSchema, new SchemaNotFoundException("Schema not found for consumer: {$consumer}"));

        return $this->mapModelToSchema($queueSchema);
    }

    /**
     * Get all schemas from the database.
     *
     * @return array<int, Schema>
     */
    public function all(): array
    {
        return QueueSchemaModel::all()
            ->map(fn (QueueSchemaModel $model) => $this->mapModelToSchema($model))
            ->toArray();
    }

    private function mapModelToSchema(QueueSchemaModel $model): Schema
    {
        return Schema::fromArray($model->toArray());
    }
}
