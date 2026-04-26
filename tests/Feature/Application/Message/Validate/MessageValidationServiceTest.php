<?php

use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidateHandler;
use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidationService;
use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidator;
use Glacueva\LaravelQueueSchema\Domain\Schema\Exception\InvalidSchemaException;
use Glacueva\LaravelQueueSchema\Domain\Schema\Schema;
use Glacueva\LaravelQueueSchema\Domain\Schema\SchemaRepository;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;

beforeEach(function (): void {
    $this->repository = Mockery::mock(SchemaRepository::class);

    $loader = new ArrayLoader;
    $translator = new Translator($loader, 'en');
    $this->validationFactory = new ValidationFactory($translator);

    $handler = new MessageValidateHandler($this->validationFactory);
    $validator = new MessageValidator($handler);

    $this->service = new MessageValidationService($this->repository, $validator);

    // Helper: Create a dummy schema mock to return from the repository
    $this->schema = Mockery::mock(Schema::class);
    $this->schema->shouldReceive('getValidationRules')->andReturn([
        'email' => ['required', 'email'],
        'amount' => ['required', 'numeric'],
    ]);
});

describe('Publisher Use Case', function (): void {
    it('validates correctly on the happy path', function (): void {
        $this->repository->shouldReceive('getByPublisher')
            ->with('billing-service')
            ->andReturn($this->schema);

        $data = ['email' => 'user@example.com', 'amount' => 150.50];

        $result = $this->service->publisher('billing-service', $data);

        expect($result)->toBe($data);
    });

    it('validates correctly and returns only validated data', function (): void {
        $this->repository->shouldReceive('getByPublisher')
            ->with('billing-service')
            ->andReturn($this->schema);

        $data = ['email' => 'user@example.com', 'amount' => 150.50, 'extra_field' => 'should be ignored'];

        $validated = $this->service->publisher('billing-service', $data);

        expect($validated)->toBe(['email' => 'user@example.com', 'amount' => 150.50]);
    });

    it('fails when a required key is missing (wrong key)', function (): void {
        $this->repository->shouldReceive('getByPublisher')
            ->with('billing-service')
            ->andReturn($this->schema);

        $data = ['email' => 'user@example.com']; // Missing 'amount'

        expect(fn () => $this->service->publisher('billing-service', $data))
            ->toThrow(InvalidSchemaException::class);
    });

    it('fails when input data is invalid (wrong input)', function (): void {
        $this->repository->shouldReceive('getByPublisher')
            ->with('billing-service')
            ->andReturn($this->schema);

        $data = ['email' => 'not-an-email', 'amount' => 150.50]; // Invalid email format

        expect(fn () => $this->service->publisher('billing-service', $data))
            ->toThrow(InvalidSchemaException::class);
    });
});

describe('Consumer Use Case', function (): void {
    it('validates correctly on the happy path', function (): void {
        $this->repository->shouldReceive('getByConsumer')
            ->with('invoice-processor')
            ->andReturn($this->schema);

        $data = ['email' => 'user@example.com', 'amount' => 150.50];

        $result = $this->service->consumer('invoice-processor', $data);

        expect($result)->toBe($data);
    });

    it('fails when a required key is missing (wrong key)', function (): void {
        $this->repository->shouldReceive('getByConsumer')
            ->with('invoice-processor')
            ->andReturn($this->schema);

        $data = ['amount' => 150.50]; // Missing 'email'

        expect(fn () => $this->service->consumer('invoice-processor', $data))
            ->toThrow(InvalidSchemaException::class);
    });

    it('fails when input data is invalid (wrong input)', function (): void {
        $this->repository->shouldReceive('getByConsumer')
            ->with('invoice-processor')
            ->andReturn($this->schema);

        $data = ['email' => 'user@example.com', 'amount' => 'one hundred']; // Amount is not numeric

        expect(fn () => $this->service->consumer('invoice-processor', $data))
            ->toThrow(InvalidSchemaException::class);
    });
});
