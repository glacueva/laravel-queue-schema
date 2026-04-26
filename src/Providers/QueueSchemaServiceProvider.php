<?php

namespace Glacueva\LaravelQueueSchema\Providers;

use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidateHandler;
use Glacueva\LaravelQueueSchema\Application\Message\Validate\MessageValidator;
use Glacueva\LaravelQueueSchema\Domain\Schema\SchemaRepository;
use Glacueva\LaravelQueueSchema\Infrastructure\Schema\Persistence\EloquentSchemaRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;

class QueueSchemaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/queue-schema.php', 'queue-schema'
        );

        // Register SchemaRepository (Eloquent only)
        $this->app->singleton(SchemaRepository::class, EloquentSchemaRepository::class);

        // Register MessageValidateHandler
        $this->app->singleton(MessageValidateHandler::class, function ($app) {
            return new MessageValidateHandler(
                $app->make(ValidationFactory::class)
            );
        });

        // Register MessageValidator
        $this->app->singleton(MessageValidator::class, function ($app) {
            return new MessageValidator(
                $app->make(MessageValidateHandler::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load and publish migrations & config
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../config/queue-schema.php' => config_path('queue-schema.php'),
        ], 'queue-schema-config');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'queue-schema-migrations');

        $this->publishes([
            __DIR__.'/../../database/seeds' => database_path('seeders'),
        ], 'queue-schema-seeders');

        $this->publishes([
            __DIR__.'/../../resources/schemas/data.json' => resource_path('schemas/data.json'),
        ], 'queue-schema-data');
    }
}
