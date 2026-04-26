<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Application\Message\Validate;

use Glacueva\LaravelQueueSchema\Domain\Schema\Exception\InvalidSchemaException;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;

readonly class MessageValidateHandler
{
    public function __construct(
        private ValidatorFactory $validatorFactory
    ) {}

    public function __invoke(MessageValidate $command): array
    {
        // Usamos la instancia inyectada en lugar del Facade
        $validator = $this->validatorFactory->make($command->data, $command->rules);

        if ($validator->fails()) {
            throw new InvalidSchemaException($validator->errors());
        }

        return $validator->validated();
    }
}
