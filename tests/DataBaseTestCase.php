<?php
/**
 * Created by enea dhack - 24/06/17 09:52 PM
 */

namespace Enea\Tests;


class DataBaseTestCase extends TestCase
{

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        // call migrations for packages upon which our package depends, e.g. Cartalyst/Sentry
        // not necessary if your package doesn't depend on another package that requires
        // running migrations for proper installation
        /* uncomment as necessary
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path'     => '../vendor/cartalyst/sentry/src/migrations',
        ]);
        */
        //$this->loadLaravelMigrations(['--database' => 'testbench']);
        // call migrations specific to our tests, e.g. to seed the db
        // the path option should be relative to the 'path.database'
        // path unless `--path` option is available.
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../src/DataBase/Migrations'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/Migrations'),
        ]);

    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'mysql',
            'database' => 'sequenceable_test',
            'host' => '127.0.0.1',
            'username' => 'nano',
            'password' => 'nano',
            'prefix'   => '',
        ]);
    }
    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            //'Cartalyst\Sentry\SentryServiceProvider',
            //'YourProject\YourPackage\YourPackageServiceProvider',
        ];
    }
    /**
     * Get package aliases.  In a normal app environment these would be added to
     * the 'aliases' array in the config/app.php file.  If your package exposes an
     * aliased facade, you should add the alias here, along with aliases for
     * facades upon which your package depends, e.g. Cartalyst/Sentry.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //'Sentry'      => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
            //'YourPackage' => 'YourProject\YourPackage\Facades\YourPackage',
        ];
    }


}