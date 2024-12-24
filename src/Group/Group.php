<?php

namespace JobMetric\Form\Group;

use JobMetric\CustomField\CustomField;
use Throwable;

class Group
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
     * Custom field within the group
     *
     * @var CustomField[] $customFields
     */
    public array $customFields = [];

    public function __construct(string $label, string $description = null, array $customFields = [])
    {
        $this->label = $label;
        $this->description = $description;
        $this->customFields = $customFields;
    }

    /**
     * render the group as HTML
     *
     * @param array $values
     *
     * @return string
     * @throws Throwable
     */
    public function render(array $values = []): string
    {
        return view('form::group', [
            'field' => $this,
            'values' => $values,
        ])->render();
    }
}
