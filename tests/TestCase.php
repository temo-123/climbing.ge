<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Tests run against the real .env MySQL database (see phpunit.xml).
    // Anything that migrates, refreshes or truncates it would destroy that
    // data, so those traits are refused outright — use DatabaseTransactions.
    private const FORBIDDEN_DB_TRAITS = [
        \Illuminate\Foundation\Testing\RefreshDatabase::class,
        \Illuminate\Foundation\Testing\DatabaseMigrations::class,
        \Illuminate\Foundation\Testing\DatabaseTruncation::class,
    ];

    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));
        foreach (self::FORBIDDEN_DB_TRAITS as $trait) {
            if (isset($uses[$trait])) {
                throw new \LogicException(static::class . ' uses ' . class_basename($trait) . ', which would wipe the dev database. Use DatabaseTransactions.');
            }
        }

        return parent::setUpTraits();
    }
}
