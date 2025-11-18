<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Http\Requests\FormTypeObjectRequest;
use JobMetric\Form\Tests\TestCase;

class FakeTypeObject
{
    use FormTypeObjectRequest;

    public string $type = 'article';

    private array $params = [];

    private array $types = [];

    public function setTypeParam(string $key, mixed $value): void
    {
        $this->types[$key] = $value;
    }

    public function getTypeParam(string $key, mixed $default = null): mixed
    {
        return $this->types[$key] ?? $default;
    }
}

class FormTypeObjectRequestTest extends TestCase
{
    public function test_render_rules_and_attributes_from_form_custom_fields(): void
    {
        $builder = new FormBuilder();
        $builder->tab(function ($tab) {
            $tab->id('a')->label('A')->selected()->startPosition()
                ->customField(function (CustomFieldBuilder $cf) {
                    $cf::text()->name('title')->label('Title')->validation('string|required');
                });
        });
        $form = $builder->build();

        $obj = new FakeTypeObject();
        $obj->setTypeParam('form', ['article' => $form]);

        $rules = [];
        $obj->renderFormFiled($rules, $form);
        $this->assertArrayHasKey('title', $rules);
        $this->assertSame('string|required', $rules['title']);

        $attributes = [];
        $obj->renderFormAttribute($attributes, $form);
        $this->assertArrayHasKey('title', $attributes);
        $this->assertSame('Title', $attributes['title']);
    }
}
