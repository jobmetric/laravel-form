<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\FormBuilder;
use JobMetric\Form\Tests\TestCase;
use Throwable;

class FormBuilderClosureTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test_build_form_via_closures_chain_and_render(): void
    {
        $builder = new FormBuilder();

        $builder
            ->action('/articles')
            ->method('PATCH')
            ->name('article-form')
            ->enctype('multipart/form-data')
            ->autocomplete()
            ->target('_blank')
            ->novalidate()
            ->class('form custom')
            ->id('article-id')
            ->removeCsrf()
            ->hiddenCustomField(function (CustomFieldBuilder $cf) {
                $cf::hidden()->name('_token_hidden');
            })
            ->tab(function ($tab) {
                $tab->id('general')
                    ->label('General')
                    ->selected()
                    ->startPosition()
                    ->group(function ($group) {
                        $group->label('Info')
                            ->description('Group description')
                            ->customField(function (CustomFieldBuilder $cf) {
                                $cf::text()->name('title')->label('Title')->validation('string|required');
                            });
                    })
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('slug')->label('Slug')->validation('string');
                    });
            })
            ->tab(function ($tab) {
                $tab->id('seo')
                    ->label('SEO')
                    ->endPosition();
            });

        $form = $builder->build();

        // Verify array structure
        $arr = $form->toArray();
        $this->assertSame('/articles', $arr['action']);
        $this->assertSame('PATCH', $arr['method']);
        $this->assertSame('article-form', $arr['name']);
        $this->assertSame('multipart/form-data', $arr['enctype']);
        $this->assertTrue($arr['autocomplete']);
        $this->assertSame('_blank', $arr['target']);
        $this->assertTrue($arr['novalidate']);
        $this->assertSame('form custom', $arr['class']);
        $this->assertSame('article-id', $arr['id']);
        $this->assertFalse($arr['csrf']);

        $this->assertCount(1, $arr['hiddenCustomField']);
        $this->assertSame('_token_hidden', $arr['hiddenCustomField'][0]['params']['name']);

        $this->assertCount(2, $arr['tabs']);
        $this->assertSame('tab-general', $arr['tabs'][0]['id']);
        $this->assertSame('General', $arr['tabs'][0]['label']);
        $this->assertSame('start', $arr['tabs'][0]['position']);
        $this->assertTrue($arr['tabs'][0]['selected']);
        $this->assertSame('tab-seo', $arr['tabs'][1]['id']);
        $this->assertSame('end', $arr['tabs'][1]['position']);

        // Verify HTML render basic markers
        $html = $form->toHtml();
        $this->assertStringContainsString('action="/articles"', $html);
        $this->assertStringContainsString('method="POST"', $html); // spoofed method for PATCH
        $this->assertStringContainsString('name="article-form"', $html);
        $this->assertStringContainsString('enctype="multipart/form-data"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('autocomplete="on"', $html);
        $this->assertStringContainsString('novalidate', $html);
        $this->assertStringContainsString('id="article-id"', $html);
        $this->assertStringContainsString('href="#tab-general"', $html);
        $this->assertStringContainsString('href="#tab-seo"', $html);
    }
}

