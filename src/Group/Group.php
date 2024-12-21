<?php

namespace JobMetric\Form\Group;

use JobMetric\CustomField\CustomField;

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
}