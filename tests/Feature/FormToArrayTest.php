<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\Form\Form;
use JobMetric\Form\Group\Group;
use JobMetric\Form\Tab\Tab;
use JobMetric\Form\Tests\TestCase;

class FormToArrayTest extends TestCase
{
    public function test_form_to_array_structure_without_custom_fields(): void
    {
        $tab1 = new Tab('tab-general', 'General', 'General desc', 'start', true, [
            new Group('Group A', 'Group A desc', []),
        ]);

        $form = new Form(
            action: '/save',
            method: 'POST',
            name: 'form-name',
            enctype: 'application/x-www-form-urlencoded',
            autocomplete: false,
            target: '_self',
            novalidate: false,
            class: 'form',
            id: 'form',
            csrf: true,
            hiddenCustomField: [],
            tabs: [$tab1]
        );

        $arr = $form->toArray();

        $this->assertIsArray($arr);
        $this->assertSame('/save', $arr['action']);
        $this->assertSame('POST', $arr['method']);
        $this->assertSame('form-name', $arr['name']);
        $this->assertSame('application/x-www-form-urlencoded', $arr['enctype']);
        $this->assertFalse($arr['autocomplete']);
        $this->assertSame('_self', $arr['target']);
        $this->assertFalse($arr['novalidate']);
        $this->assertSame('form', $arr['class']);
        $this->assertSame('form', $arr['id']);
        $this->assertTrue($arr['csrf']);
        $this->assertIsArray($arr['hiddenCustomField']);
        $this->assertIsArray($arr['tabs']);
        $this->assertSame('tab-general', $arr['tabs'][0]['id']);
        $this->assertSame('General', $arr['tabs'][0]['label']);
        $this->assertSame('start', $arr['tabs'][0]['position']);
        $this->assertTrue($arr['tabs'][0]['selected']);
        $this->assertIsArray($arr['tabs'][0]['fields']);
        $this->assertSame('group', $arr['tabs'][0]['fields'][0]['kind']);
    }

    public function test_tab_to_array_with_non_group_field_placeholder(): void
    {
        // Using a generic object to simulate a custom field structure for array serialization
        $fakeField = (object) [
            'label' => 'Field A',
            'params' => ['name' => 'field_a'],
            'validation' => 'string',
        ];

        $tab = new Tab('tab-advanced', 'Advanced', null, 'end', false, [
            $fakeField,
        ]);

        $arr = $tab->toArray();

        $this->assertSame('tab-advanced', $arr['id']);
        $this->assertSame('Advanced', $arr['label']);
        $this->assertSame('end', $arr['position']);
        $this->assertFalse($arr['selected']);
        $this->assertSame('custom_field', $arr['fields'][0]['kind']);
        $this->assertSame('Field A', $arr['fields'][0]['data']['label']);
        $this->assertSame(['name' => 'field_a'], $arr['fields'][0]['data']['params']);
        $this->assertSame('string', $arr['fields'][0]['data']['validation']);
    }
}

