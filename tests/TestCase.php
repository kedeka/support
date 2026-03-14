<?php

namespace Kedeka\Support\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kedeka\Support\SupportServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kedeka\\Support\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    protected function setUpDatabase(): void
    {
        Schema::create('sluggable_models', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('ulid_models', function (Blueprint $table) {
            $table->id();
            $table->string('ulid', 26)->unique()->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('ulid_primary_models', function (Blueprint $table) {
            $table->string('id', 26)->primary();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }
}
