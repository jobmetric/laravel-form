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
     * Render the group as HTML
     *
     * @param array $values
     *
     * @return string
     * @throws Throwable
     */
    public function toHtml(array $values = []): string
    {
        return view('form::group', [
            'field' => $this,
            'values' => $values,
        ])->render();
    }

    /**
     * Convert group definition to array for API output
     *
     * @return array{
     *     label: string,
     *     description: string|null,
     *     customFields: array<int, array{label: mixed, params: array, validation: mixed}>
     * }
     */
    public function toArray(): array
    {
        $customFields = array_map(function (CustomField $field) {
            return [
                'label' => $field->label ?? null,
                'params' => $field->params ?? [],
                'validation' => $field->validation ?? null,
            ];
        }, $this->customFields ?? []);

        return [
            'label' => $this->label,
            'description' => $this->description,
            'customFields' => $customFields,
        ];
    }
}
