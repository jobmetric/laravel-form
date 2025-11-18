<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\CustomField\Option\OptionBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Tests\TestCase;
use Throwable;

class ComplexFormStructureTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test_complex_form_with_multiple_tabs_groups_and_fields(): void
    {
        $builder = new FormBuilder();

        $builder
            ->name('complex-form')
            ->tab(function ($tab) {
                $tab->id('general')->label('General')->selected()->startPosition()
                    ->group(function ($group) {
                        $group->label('Info')
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::text()->name('title')->label('Title')->validation('string|required');
                            })
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::text()->name('slug')->label('Slug');
                            });
                    })
                    ->group(function ($group) {
                        $group->label('Meta')
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::number()->name('order')->label('Order');
                            });
                    });
            })
            ->tab(function ($tab) {
                $tab->id('details')->label('Details')->endPosition()
                    ->group(function ($group) {
                        $group->label('SEO')
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::select()->name('status')->label('Status')->options(function (OptionBuilder $opt) {
                                    $opt->label('Draft')->value('draft')->build();
                                    $opt->label('Published')->value('published')->selected()->build();
                                });
                            })
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::text()->name('keywords')->label('Keywords');
                            });
                    })
                    ->group(function ($group) {
                        $group->label('Extra')
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::text()->name('note')->label('Note');
                            });
                    });
            });

        $form = $builder->build();

        $arr = $form->toArray();
        $this->assertSame('complex-form', $arr['name']);
        $this->assertCount(2, $arr['tabs']);

        $general = $arr['tabs'][0];
        $this->assertSame('tab-general', $general['id']);
        $this->assertSame('General', $general['label']);
        $this->assertTrue($general['selected']);
        $this->assertSame('start', $general['position']);
        $this->assertCount(2, $general['fields']);
        $this->assertSame('group', $general['fields'][0]['kind']);
        $this->assertSame('group', $general['fields'][1]['kind']);
        $this->assertCount(2, $general['fields'][0]['data']['customFields']);
        $this->assertCount(1, $general['fields'][1]['data']['customFields']);
        $this->assertSame('title', $general['fields'][0]['data']['customFields'][0]['params']['name']);
        $this->assertSame('slug', $general['fields'][0]['data']['customFields'][1]['params']['name']);
        $this->assertSame('order', $general['fields'][1]['data']['customFields'][0]['params']['name']);

        $details = $arr['tabs'][1];
        $this->assertSame('tab-details', $details['id']);
        $this->assertSame('Details', $details['label']);
        $this->assertFalse($details['selected']);
        $this->assertSame('end', $details['position']);
        $this->assertCount(2, $details['fields']);
        $this->assertCount(2, $details['fields'][0]['data']['customFields']);
        $this->assertCount(1, $details['fields'][1]['data']['customFields']);
        $this->assertSame('status', $details['fields'][0]['data']['customFields'][0]['params']['name']);
        $this->assertSame('keywords', $details['fields'][0]['data']['customFields'][1]['params']['name']);
        $this->assertSame('note', $details['fields'][1]['data']['customFields'][0]['params']['name']);

        $html = $form->toHtml();
        $this->assertStringContainsString('href="#tab-general"', $html);
        $this->assertStringContainsString('href="#tab-details"', $html);
        $this->assertStringContainsString('data-label-colored="In"', $html);
        $this->assertTrue(
            str_contains($html, 'data-label-colored="ME"') || str_contains($html, 'data-label-colored="Me"')
        );
        $this->assertStringContainsString('name="title"', $html);
        $this->assertStringContainsString('name="slug"', $html);
        $this->assertStringContainsString('name="order"', $html);
        $this->assertStringContainsString('name="status"', $html);
        $this->assertStringContainsString('name="keywords"', $html);
        $this->assertStringContainsString('name="note"', $html);
        $this->assertStringContainsString('<option value="draft"', $html);
        $this->assertStringContainsString('<option value="published" selected', $html);
    }
}
