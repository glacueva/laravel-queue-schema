<?php

namespace Glacueva\LaravelQueueSchema\Domain\Schema;

interface SchemaRepository
{
    /* Get schema by id.
     *
     * @throws Exception\SchemaNotFoundException
     */
    public function getById(string $id): Schema;

    /**
     * Get schema by publisher.
     *
     * @throws Exception\SchemaNotFoundException
     */
    public function getByPublisher(string $publisher): Schema;

    /**
     * Get schema by consumer.
     *
     * @throws Exception\SchemaNotFoundException
     */
    public function getByConsumer(string $consumer): Schema;

    /**
     * Get all schemas.
     *
     * @return array<int, Schema>
     */
    public function all(): array;
}
