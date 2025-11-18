<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomField;
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Tests\TestCase;
use Throwable;

 class FormBuilderCollectCustomFieldsTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test_collect_all_custom_fields_from_builder_and_form(): void
    {
        $builder = new FormBuilder();

        $builder
            ->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
                $fieldBuilder::hidden()->name('_token_hidden');
            })
            ->tab(function ($tab) {
                $tab->id('general')->label('General')->startPosition()
                    ->group(function ($group) {
                        $group->label('Info')
                            ->customField(function (CustomFieldBuilder $fieldBuilder) {
                                $fieldBuilder::text()->name('title');
                            });
                    })
                    ->customField(function (CustomFieldBuilder $fieldBuilder) {
                        $fieldBuilder::text()->name('slug');
                    });
            })
            ->tab(function ($tab) {
                $tab->id('meta')->label('Meta')->endPosition()
                    ->group(function ($group) {
                        $group->label('Settings')
                            ->customField(function (CustomFieldBuilder $fieldBuilder) {
                                $fieldBuilder::number()->name('order');
                            });
                    });
            });

        $builderFields = $builder->getAllCustomFields();
        $this->assertCount(4, $builderFields);
        $this->assertContainsOnlyInstancesOf(CustomField::class, $builderFields);

        $names = array_map(fn(CustomField $field) => $field->params['name'] ?? null, $builderFields);
        $this->assertEquals(['_token_hidden', 'title', 'slug', 'order'], $names);

        $form = $builder->build();

        $formFields = $form->getAllCustomFields();
        $this->assertCount(4, $formFields);
        $this->assertContainsOnlyInstancesOf(CustomField::class, $formFields);
        $formNames = array_map(fn(CustomField $field) => $field->params['name'] ?? null, $formFields);
        $this->assertEquals(['_token_hidden', 'title', 'slug', 'order'], $formNames);
    }

    /**
     * @throws Throwable
     */
    public function test_collect_custom_fields_without_hidden_fields(): void
    {
        $builder = new FormBuilder();

        $builder
            ->hiddenCustomField(function (CustomFieldBuilder $fieldBuilder) {
                $fieldBuilder::hidden()->name('_token_hidden');
            })
            ->tab(function ($tab) {
                $tab->id('general')->label('General')->startPosition()
                    ->customField(function (CustomFieldBuilder $fieldBuilder) {
                        $fieldBuilder::text()->name('slug');
                    })
                    ->group(function ($group) {
                        $group->label('Info')
                            ->customField(function (CustomFieldBuilder $fieldBuilder) {
                                $fieldBuilder::text()->name('title');
                            });
                    });
            });

        $withoutHidden = $builder->getAllCustomFields(includeHidden: false);
        $names = array_map(fn(CustomField $field) => $field->params['name'] ?? null, $withoutHidden);

        $this->assertEquals(['slug', 'title'], $names);

        $form = $builder->build();
        $formWithoutHidden = $form->getAllCustomFields(includeHidden: false);
        $formNames = array_map(fn(CustomField $field) => $field->params['name'] ?? null, $formWithoutHidden);

        $this->assertEquals(['slug', 'title'], $formNames);
    }

    /**
     * @throws Throwable
     */
    public function test_tab_builder_collects_group_and_direct_custom_fields_in_order(): void
    {
        $tabBuilder = new \JobMetric\Form\Tab\TabBuilder();

        $tabBuilder->id('sample')->label('Sample');

        $tabBuilder->group(function ($group) {
            $group->label('Info')
                ->customField(function (CustomFieldBuilder $fieldBuilder) {
                    $fieldBuilder::text()->name('title');
                });
        });

        $tabBuilder->customField(function (CustomFieldBuilder $fieldBuilder) {
            $fieldBuilder::text()->name('slug');
        });

        $fields = $tabBuilder->getCustomFields();

        $this->assertCount(2, $fields);
        $this->assertEquals('title', $fields[0]->params['name']);
        $this->assertEquals('slug', $fields[1]->params['name']);
    }
}
