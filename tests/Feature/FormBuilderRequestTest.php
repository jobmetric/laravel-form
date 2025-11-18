<?php

namespace JobMetric\Form\Tests\Feature;

use InvalidArgumentException;
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Http\Requests\FormBuilderRequest;
use JobMetric\Form\Tests\TestCase;

class FormBuilderRequestTest extends TestCase
{
    public function test_rules_and_attributes_are_generated_from_form_builder(): void
    {
        $builder = new FormBuilder();

        $builder->tab(function ($tab) {
            $tab->id('general')->label('General')->startPosition()
                ->customField(function (CustomFieldBuilder $fieldBuilder) {
                    $fieldBuilder::text()->name('title')->label('Title')->validation('string|required');
                })
                ->customField(function (CustomFieldBuilder $fieldBuilder) {
                    $fieldBuilder::text()->name('slug')->label('Slug');
                });
        });

        $formRequest = new class($builder) extends FormBuilderRequest {
            public function authorize(): bool
            {
                return true;
            }
        };

        $rules = $formRequest->rules();

        $this->assertArrayHasKey('title', $rules);
        $this->assertSame('string|required', $rules['title']);
        $this->assertArrayHasKey('slug', $rules);
        $this->assertSame('string|nullable|sometimes', $rules['slug']);

        $attributes = $formRequest->attributes();
        $this->assertSame('Title', $attributes['title']);
        $this->assertSame('Slug', $attributes['slug']);
    }

    public function test_rules_respect_hidden_field_toggle(): void
    {
        $builder = new FormBuilder();

        $builder
            ->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
                $fieldBuilder::hidden()->name('_token_hidden');
            })
            ->tab(function ($tab) {
                $tab->id('general')->label('General')->startPosition()
                    ->customField(function (CustomFieldBuilder $fieldBuilder) {
                        $fieldBuilder::text()->name('title')->label('Title');
                    });
            });

        $formRequest = new class($builder) extends FormBuilderRequest {
            public function authorize(): bool
            {
                return true;
            }
        };

        $formRequest->includeHiddenFields(false);

        $rules = $formRequest->rules();

        $this->assertArrayNotHasKey('_token_hidden', $rules);
        $this->assertArrayHasKey('title', $rules);
    }

    public function test_constructor_sets_builder_and_hidden_flag(): void
    {
        $builder = new FormBuilder();

        $builder
            ->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
                $fieldBuilder::hidden()->name('_token_hidden');
            })
            ->tab(function ($tab) {
                $tab->id('general')->label('General')->startPosition()
                    ->customField(function (CustomFieldBuilder $fieldBuilder) {
                        $fieldBuilder::text()->name('title')->label('Title');
                    });
            });

        $formRequest = new class($builder, false) extends FormBuilderRequest {
            public function authorize(): bool
            {
                return true;
            }
        };

        $rules = $formRequest->rules();

        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayNotHasKey('_token_hidden', $rules);
    }

}
