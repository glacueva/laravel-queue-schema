<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Domain\Schema\Exception;

use Exception;
use Illuminate\Contracts\Support\MessageBag;

class InvalidSchemaException extends Exception
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $errors;

    /**
     * @param  MessageBag|array<string, array<int, string>>  $errors
     */
    public function __construct(MessageBag|array $errors)
    {
        // Convert MessageBag to array if needed
        if ($errors instanceof MessageBag) {
            $this->errors = $errors->toArray();
        } else {
            $this->errors = $errors;
        }

        $errorMessage = $this->formatErrors($this->errors);
        parent::__construct("Schema validation failed: {$errorMessage}");
    }

    /**
     * Get validation errors.
     *
     * @return array<string, array<int, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Format errors for display.
     *
     * @param  array<string, array<int, string>>  $errors
     */
    private function formatErrors(array $errors): string
    {
        $formatted = [];

        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $formatted[] = "{$field}: {$message}";
            }
        }

        return implode('; ', $formatted);
    }
}
