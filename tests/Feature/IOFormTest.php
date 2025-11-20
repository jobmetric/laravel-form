<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Support\IOForm;
use JobMetric\Form\Tests\TestCase;

class IOFormTest extends TestCase
{
    public function test_normalize_filters_only_defined_fields(): void
    {
        $builder = $this->makeBuilder();

        $payload = [
            '_token_hidden' => 'token',
            'title' => 'Hello',
            'slug' => 'form-io',
            'ignored' => 'nope',
        ];

        $this->assertSame([
            '_token_hidden' => 'token',
            'title' => 'Hello',
            'slug' => 'form-io',
        ], IOForm::normalize($builder, $payload));
    }

    public function test_hidden_fields_can_be_excluded(): void
    {
        $builder = $this->makeBuilder();

        $payload = [
            '_token_hidden' => 'token',
            'title' => 'Hello',
            'slug' => 'form-io',
        ];

        $result = IOForm::normalize($builder, $payload, includeHidden: false);

        $this->assertSame([
            'title' => 'Hello',
            'slug' => 'form-io',
        ], $result);
        $this->assertArrayNotHasKey('_token_hidden', $result);
    }

    public function test_for_store_is_alias_of_normalize(): void
    {
        $builder = $this->makeBuilder();
        $payload = [
            'title' => 'Persist me',
            'slug' => 'persist-me',
            'extra' => 'ignored',
        ];

        $this->assertSame(
            IOForm::normalize($builder, $payload, includeHidden: false),
            IOForm::forStore($builder, $payload, includeHidden: false)
        );
    }

    public function test_to_html_prefills_normalized_values(): void
    {
        $builder = $this->makeBuilder();

        $html = IOForm::toHtml($builder, [
            '_token_hidden' => 'token',
            'title' => 'Hello Html',
            'slug' => 'html-slug',
            'ignored' => 'nope',
        ]);

        $this->assertStringContainsString('name="title"', $html);
        $this->assertStringContainsString('value="Hello Html"', $html);
        $this->assertStringContainsString('name="slug"', $html);
        $this->assertStringContainsString('value="html-slug"', $html);
        $this->assertStringNotContainsString('ignored', $html);
    }

    public function test_to_array_returns_normalized_values(): void
    {
        $builder = $this->makeBuilder();

        $payload = [
            '_token_hidden' => 'token',
            'title' => 'Array Value',
            'slug' => 'array-value',
            'ignored' => 'nope',
        ];

        $this->assertSame([
            '_token_hidden' => 'token',
            'title' => 'Array Value',
            'slug' => 'array-value',
        ], IOForm::toArray($builder, $payload));
    }

    public function test_accepts_built_form_instances(): void
    {
        $builder = $this->makeBuilder();
        $form = $builder->build();

        $payload = [
            '_token_hidden' => 'token',
            'title' => 'With Form',
            'slug' => 'with-form',
            'ignored' => 'nope',
        ];

        $fromBuilder = IOForm::normalize($builder, $payload);
        $fromForm = IOForm::normalize($form, $payload);

        $this->assertSame($fromBuilder, $fromForm);
    }

    private function makeBuilder(): FormBuilder
    {
        $builder = new FormBuilder();

        $builder
            ->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
                $fieldBuilder::hidden()->name('_token_hidden');
            })
            ->tab(function ($tab) {
                $tab->id('content')->label('Content')->startPosition()
                    ->customField(function (CustomFieldBuilder $fieldBuilder) {
                        $fieldBuilder::text()->name('title')->label('Title')->validation('string|required');
                    })
                    ->customField(function (CustomFieldBuilder $fieldBuilder) {
                        $fieldBuilder::text()->name('slug')->label('Slug');
                    });
            });

        return $builder;
    }
}
