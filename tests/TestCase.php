<?php

namespace JobMetric\Form\Tests;

use JobMetric\CustomField\CustomFieldServiceProvider;
use JobMetric\Form\FormServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Support\ViewErrorBag;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            CustomFieldServiceProvider::class,
            FormServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('app.fallback_locale', 'en');

        // Ensure the 'form' view namespace resolves to this package's views during tests
        $app['view']->addNamespace('form', base_path('resources/views'));

        // Provide an empty error bag for field views expecting $errors
        $app['view']->share('errors', new ViewErrorBag());
    }
}
