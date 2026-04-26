<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Application\Message\Validate;

use Glacueva\LaravelQueueSchema\Domain\Schema\Exception\SchemaNotFoundException;
use Glacueva\LaravelQueueSchema\Domain\Schema\SchemaRepository;

readonly class MessageValidationService
{
    public function __construct(
        private SchemaRepository $schemaRepository,
        private MessageValidator $messageValidator,
    ) {}

    /**
     * Validate a message by consumer.
     *
     * @param  string  $consumer  The consumer identifier
     * @param  array  $message  The message data to validate
     * @return array The validated message data
     *
     * @throws SchemaNotFoundException
     * @throws InvalidSchemaException
     */
    public function consumer(string $consumer, array $message): array
    {
        $schema = $this->schemaRepository->getByConsumer($consumer);

        return $this->messageValidator->__invoke($schema->getValidationRules(), $message);
    }

    /**
     * Validate a message by publisher.
     *
     * @param  string  $publisher  The publisher identifier
     * @param  array  $message  The message data to validate
     * @return array The validated message data
     *
     * @throws SchemaNotFoundException
     * @throws InvalidSchemaException
     */
    public function publisher(string $publisher, array $message): array
    {
        $schema = $this->schemaRepository->getByPublisher($publisher);

        return $this->messageValidator->__invoke($schema->getValidationRules(), $message);
    }
}
