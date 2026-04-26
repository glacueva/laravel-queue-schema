<?php

use Glacueva\LaravelQueueSchema\Tests\TestCase;

it('can run tests', function (): void {
    expect(true)->toBeTrue();
});

it('can perform basic assertions', function (): void {
    expect(1 + 1)->toBe(2);
    expect('hello')->toContain('ell');
});

class ExampleTest extends TestCase
{
    public function test_example()
    {
        $this->assertTrue(true);
    }
}
