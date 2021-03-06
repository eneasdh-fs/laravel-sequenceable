<?php
/**
 * Created by enea dhack - 24/06/17 09:52 PM.
 */

namespace Enea\Tests;

use Orchestra\Database\ConsoleServiceProvider;

class DatabaseTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ConsoleServiceProvider::class
        ];
    }
}
