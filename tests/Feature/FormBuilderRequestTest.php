<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Http\Requests\FormBuilderRequest;
use JobMetric\Form\Tests\TestCase;

class FormBuilderRequestTest extends TestCase
{
    public function test_messages_are_generated_from_custom_field_definitions(): void
    {
        // Build a Form with one tab and a custom field that defines messages
        $field = (object) [
            'label'      => 'Title',
            'params'     => [
                'name'     => 'title',
                // Provide messages via params to exercise both scoped and fully-qualified keys
                'messages' => [
                    'required'    => 'Title is required.',              // becomes title.required
                    'min.string'  => 'Title too short.',               // becomes title.min.string
                    'title.alpha' => 'Title must be alpha only.',     // already fully-qualified
                    0             => 'ignored',                                   // non-string key should be ignored
                ],
            ],
            'validation' => 'required|string|min:3',
        ];

        $tab = new \JobMetric\Form\Tab\Tab('tab-general', 'General', null, 'start', true, [$field]);
        $form = new \JobMetric\Form\Form(null, 'POST', 'form', 'application/x-www-form-urlencoded', false, '_self', false, 'form d-flex flex-column flex-lg-row', 'form', true, [], [$tab]);

        // Provide a stub builder that returns our prepared form
        $stubBuilder = new class($form) extends \JobMetric\Form\FormBuilder
        {
            public function __construct(private \JobMetric\Form\Form $fake)
            {
            }
            public function build(): \JobMetric\Form\Form
            {
                return $this->fake;
            }
        };

        $formRequest = new class($stubBuilder) extends \JobMetric\Form\Http\Requests\FormBuilderRequest
        {
            public function authorize(): bool
            {
                return true;
            }
        };

        $messages = $formRequest->messages();

        $this->assertArrayHasKey('title.required', $messages);
        $this->assertSame('Title is required.', $messages['title.required']);

        $this->assertArrayHasKey('title.min.string', $messages);
        $this->assertSame('Title too short.', $messages['title.min.string']);

        $this->assertArrayHasKey('title.alpha', $messages);
        $this->assertSame('Title must be alpha only.', $messages['title.alpha']);

        // Sanity: messages() returns a non-empty array with our mapped keys
        $this->assertNotEmpty($messages);
    }
    public function test_rules_and_attributes_are_generated_from_form_builder(): void
    {
        $builder = new FormBuilder();

        $builder->tab(function ($tab) {
            $tab->id('general')->label('General')->startPosition()->customField(function (
                CustomFieldBuilder $fieldBuilder
            ) {
                $fieldBuilder::text()->name('title')->label('Title')->validation('string|required');
            })->customField(function (CustomFieldBuilder $fieldBuilder) {
                $fieldBuilder::text()->name('slug')->label('Slug');
            });
        });

        $formRequest = new class($builder) extends FormBuilderRequest
        {
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

        $builder->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
            $fieldBuilder::hidden()->name('_token_hidden');
        })->tab(function ($tab) {
            $tab->id('general')->label('General')->startPosition()->customField(function (
                CustomFieldBuilder $fieldBuilder
            ) {
                $fieldBuilder::text()->name('title')->label('Title');
            });
        });

        $formRequest = new class($builder) extends FormBuilderRequest
        {
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

        $builder->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
            $fieldBuilder::hidden()->name('_token_hidden');
        })->tab(function ($tab) {
            $tab->id('general')->label('General')->startPosition()->customField(function (
                CustomFieldBuilder $fieldBuilder
            ) {
                $fieldBuilder::text()->name('title')->label('Title');
            });
        });

        $formRequest = new class($builder, false) extends FormBuilderRequest
        {
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
