<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Application\Message\Validate;

readonly class MessageValidate
{
    public function __construct(
        public array $rules,
        public array $data,
    ) {}
}
