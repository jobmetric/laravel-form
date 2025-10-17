<?php

namespace JobMetric\Form\Tests\Feature;

use JobMetric\Form\Form;
use JobMetric\Form\Group\Group;
use JobMetric\Form\Tab\Tab;
use JobMetric\Form\Tests\TestCase;
use Throwable;

class TabAndGroupHtmlTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test_form_renders_tabs_and_group_html(): void
    {
        $tab = new Tab('tab-info', 'Info', 'Info desc', 'start', true, [
            new Group('Group Title', 'Group Desc', []),
        ]);

        $form = new Form(
            action: '/save',
            method: 'POST',
            name: 'form-name',
            tabs: [$tab]
        );

        $html = $form->toHtml();

        // Tab links present
        $this->assertStringContainsString('nav-link', $html);
        $this->assertStringContainsString('href="#tab-info"', $html);

        // Active tab pane rendered
        $this->assertStringContainsString('id="tab-info"', $html);
        $this->assertStringContainsString('tab-pane', $html);

        // Group section present
        $this->assertStringContainsString('data-label-colored="Gr"', $html);
        $this->assertStringContainsString('data-label-noncolored="oup Title"', $html);
        $this->assertStringContainsString('alert alert-info', $html); // description wrapper
    }
}
