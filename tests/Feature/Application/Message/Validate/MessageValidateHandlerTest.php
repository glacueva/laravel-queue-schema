<?php

use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidate;
use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidateHandler;
use Glacueva\LaravelQueueSchema\Domain\Schema\Exception\InvalidSchemaException;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;

beforeEach(function (): void {
    $loader = new ArrayLoader;
    $translator = new Translator($loader, 'en');
    $this->validationFactory = new ValidationFactory($translator);
    $this->handler = new MessageValidateHandler($this->validationFactory);
});

it('validates a message successfully on the happy path', function (): void {
    $rules = [
        'name' => ['required', 'string'],
        'age' => ['required', 'integer'],
    ];
    $data = [
        'name' => 'John Doe',
        'age' => 30,
    ];

    $command = new MessageValidate($rules, $data);
    $result = ($this->handler)($command);

    expect($result)->toBe($data);
});

it('throws an exception when a required key is missing (wrong key)', function (): void {
    $rules = [
        'name' => ['required', 'string'],
        'age' => ['required', 'integer'],
    ];
    $data = [
        'name' => 'John Doe',
    ]; // Missing the 'age' key

    $command = new MessageValidate($rules, $data);

    expect(fn () => ($this->handler)($command))
        ->toThrow(InvalidSchemaException::class);
});

it('throws an exception when the input type is incorrect (wrong input)', function (): void {
    $rules = [
        'name' => ['required', 'string'],
        'age' => ['required', 'integer'],
    ];
    $data = [
        'name' => 'John Doe',
        'age' => 'thirty',
    ]; // 'age' is a string, but an integer is expected

    $command = new MessageValidate($rules, $data);

    expect(fn () => ($this->handler)($command))
        ->toThrow(InvalidSchemaException::class);
});
