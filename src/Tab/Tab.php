<?php

namespace JobMetric\Form\Tab;

use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\Group\GroupBuilder;

class Tab
{
    /**
     * Attribute label
     *
     * @var string $label
     */
    public string $label;

    /**
     * Attribute description
     *
     * @var string|null $description
     */
    public string|null $description = null;

    /**
     * Fields within the tab
     *
     * @var array $fields
     */
    public array $fields = [];

    public function __construct(string $label, string $description = null, array $fields = [])
    {
        $this->label = $label;
        $this->description = $description;
        $this->fields = $fields;
    }
}
