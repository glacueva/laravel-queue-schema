<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Tests\Mothers;

use Faker\Factory as FakerFactory;

class MessageMother
{
    public static function create(string $routingKey, array $data = []): object
    {
        if (empty($data)) {
            $faker = FakerFactory::create();
            $data = [
                'email' => $faker->email(),
                'user_id' => $faker->randomDigitNotNull(),
                'updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
            ];
        }

        return (object) [
            'routingKey' => $routingKey,
            'data' => $data,
        ];
    }

    public static function consumed(string $routingKey, array $data = []): object
    {
        return self::create($routingKey, $data);
    }

    public static function published(string $routingKey, array $data = []): object
    {
        return self::create($routingKey, $data);
    }

    public static function validPayload(): array
    {
        $faker = FakerFactory::create();

        return [
            'email' => $faker->email(),
            'user_id' => $faker->numberBetween(1, 1000),
            'updated_at' => $faker->dateTime()->format('Y-m-d'),
        ];
    }

    public static function invalidPayload(): array
    {
        return [
            'email' => 'not-an-email',
            'user_id' => 'not-an-integer',
            'updated_at' => 'invalid-date',
        ];
    }

    public static function payloadMissingRequired(): array
    {
        $faker = FakerFactory::create();

        return [
            'email' => $faker->email(),
            // missing user_id
            'updated_at' => $faker->dateTime()->format('Y-m-d'),
        ];
    }
}
