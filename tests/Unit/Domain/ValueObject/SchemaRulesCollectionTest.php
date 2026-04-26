<?php

declare(strict_types=1);

namespace Glacueva\LaravelQueueSchema\Tests\Unit\Domain\ValueObject;

use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\SchemaRule;
use Glacueva\LaravelQueueSchema\Domain\Schema\ValueObject\SchemaRulesCollection;

describe('SchemaRulesCollection', function (): void {
    describe('fromArray', function (): void {
        it('creates collection from array with correct field and rules keys', function (): void {
            $rulesArray = [
                [
                    'field' => 'email',
                    'validation' => ['required', 'email'],
                ],
                [
                    'field' => 'user_id',
                    'validation' => ['required', 'integer'],
                ],
            ];

            $collection = SchemaRulesCollection::fromArray($rulesArray);

            expect($collection)->toBeInstanceOf(SchemaRulesCollection::class);
            expect($collection->count())->toBe(2);

            $rules = $collection->toArray();
            expect($rules[0]->field)->toBe('email');
            expect($rules[1]->field)->toBe('user_id');
        });
    });

    describe('toArrayOfArrays', function (): void {
        it('converts collection to array of arrays with field and validation keys', function (): void {
            $rulesArray = [
                [
                    'field' => 'email',
                    'validation' => ['required', 'email'],
                ],
                [
                    'field' => 'user_id',
                    'validation' => ['required', 'integer'],
                ],
            ];

            $collection = SchemaRulesCollection::fromArray($rulesArray);
            $result = $collection->toArrayOfArrays();

            expect($result)->toHaveCount(2);
            expect($result[0])->toHaveKeys(['field', 'validation']);
            expect($result[0]['field'])->toBe('email');
            expect($result[0]['validation'])->toEqual(['required', 'email']);
            expect($result[1]['field'])->toBe('user_id');
            expect($result[1]['validation'])->toEqual(['required', 'integer']);
        });
    });

    describe('iteration', function (): void {
        it('is iterable', function (): void {
            $rulesArray = [
                ['field' => 'email', 'validation' => ['required', 'email']],
                ['field' => 'user_id', 'validation' => ['required', 'integer']],
            ];

            $collection = SchemaRulesCollection::fromArray($rulesArray);
            $count = 0;

            foreach ($collection as $rule) {
                expect($rule)->toBeInstanceOf(SchemaRule::class);
                $count++;
            }

            expect($count)->toBe(2);
        });
    });
});
