<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Application\Message\Validate;

readonly class MessageValidator
{
    public function __construct(
        private MessageValidateHandler $messageValidateHandler,
    ) {}

    public function __invoke(array $rules, array $data): array
    {
        return $this->messageValidateHandler->__invoke(
            new MessageValidate($rules, $data)
        );
    }
}
