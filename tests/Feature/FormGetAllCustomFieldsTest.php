<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Tests\TestCase;

class FormGetAllCustomFieldsTest extends TestCase
{
    public function test_get_all_custom_fields_includes_group_and_direct_fields(): void
    {
        $form = (new FormBuilder())
            ->tab(function ($tab) {
                $tab->id('a')->label('A')->selected()->startPosition()
                    ->group(function ($group) {
                        $group->label('G')
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::text()->name('in_group')->label('In Group');
                            });
                    })
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('direct')->label('Direct');
                    });
            })
            ->build();

        $all = $form->getAllCustomFields();
        $this->assertIsArray($all);
        $names = array_map(fn($f) => $f->params['name'] ?? null, $all);
        sort($names);
        $this->assertSame(['direct', 'in_group'], $names);
    }
}

