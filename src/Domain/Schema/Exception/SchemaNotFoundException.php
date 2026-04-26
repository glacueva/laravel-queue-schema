<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Domain\Schema\Exception;

class SchemaNotFoundException extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct("Schema with key '{$key}' not found.");
    }
}
