<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\Form\Form;
use JobMetric\Form\Tab\Tab;
use JobMetric\Form\Tests\TestCase;
use Throwable;

class FormHtmlAttributesTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test_form_renders_with_all_attributes(): void
    {
        $form = new Form(
            action: '/submit',
            method: 'PUT',
            name: 'my-form',
            enctype: 'multipart/form-data',
            autocomplete: true,
            target: '_blank',
            novalidate: true,
            class: 'form test-class',
            id: 'form-id',
            csrf: true,
            hiddenCustomField: [],
            tabs: [
                new Tab('tab-main', 'Main', null, 'start', true, []),
            ]
        );

        $html = $form->toHtml();

        $this->assertStringContainsString('<form', $html);
        $this->assertTrue(
            str_contains($html, 'method="PUT"') || str_contains($html, 'method="POST"')
        );
        $this->assertTrue(
            str_contains($html, 'name="_method"') && str_contains($html, 'value="PUT"')
        );

        $this->assertStringContainsString('action="/submit"', $html);
        $this->assertStringContainsString('name="my-form"', $html);
        $this->assertStringContainsString('enctype="multipart/form-data"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('autocomplete="on"', $html);
        $this->assertStringContainsString('novalidate', $html);
        $this->assertStringContainsString('class="form test-class"', $html);
        $this->assertStringContainsString('id="form-id"', $html);
    }
}
