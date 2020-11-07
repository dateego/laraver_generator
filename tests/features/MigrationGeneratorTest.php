<?php

namespace Dateego\Generators\Tests\Features;

use Illuminate\Console\Application;
use Illuminate\Contracts\Console\Kernel;
use Orchestra\Testbench\TestCase;


class MigrationGeneratorTest extends TestCase
{


    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('generators', [
            'migration_template_path' => __DIR__ . '/../../src/templates/migration.txt',
            'migration_target_path'   => base_path('database/migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Dateego\Generators\Tests\Stubs\Providers\ServiceProvider::class,
            \Dateego\Generators\GeneratorsServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [

        ];
    }

    /** @test */
    public function it_generate_the_migrations()
    {
        $this->artisan('dateego:generate.migration', ['tables' => 'test_users', '--no-interaction' => true]);

//        $user = \DB::table('test_users')->where('id', '=', 1)->first();
//
//        $this->assertEquals('hello@orchestraplatform.com', $user->email);
//        $this->assertTrue(\Hash::check('123', $user->password));
//
//        $this->assertEquals([
//            'id',
//            'email',
//            'password',
//            'created_at',
//            'updated_at',
//        ], \Schema::getColumnListing('test_users'));
    }

//    /**
//     * @test
//     */
//    public function iGenerateAMigrationWithNameAndFields()
//    {
//        $application = new Application();
//        $command = $this->app->make(\Dateego\Generators\Database\Console\Migrations\MigrationGenerateCommand::class);
//        $command->setLaravel(app());
//        $application->add($command);
//
//        $this->tester = new CommandTester($command);
//
//        $this->tester->execute([
//            'tables' => 'order,user',
//            '--ignore' => [],
//            '--path' => __DIR__.'/../../tmp'
//        ]);
//    }


}

