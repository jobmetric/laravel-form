<?php

namespace JobMetric\Form\Tab;

use JobMetric\Form\Group\Group;
use Throwable;

class Tab
{
    /**
     * Attribute id
     *
     * @var string $id
     */
    public string $id;

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
     * Attribute position
     *
     * @var string $position
     */
    public string $position = 'start';

    /**
     * Attribute selected
     *
     * @var bool $selected
     */
    public bool $selected = false;

    /**
     * Fields within the tab
     *
     * @var array $fields
     */
    public array $fields = [];

    public function __construct(string $id, string $label, string $description = null, string $position = 'start', bool $selected = false, array $fields = [])
    {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
        $this->position = $position;
        $this->selected = $selected;
        $this->fields = $fields;
    }

    /**
     * Render the tab link as HTML
     *
     * @return string
     * @throws Throwable
     */
    public function toHtmlLink(): string
    {
        return view('form::tab-link', [
            'field' => $this,
        ])->render();
    }

    /**
     * Render the tab data as HTML
     *
     * @param array $values
     *
     * @return string
     * @throws Throwable
     */
    public function toHtmlData(array $values = []): string
    {
        return view('form::tab-data', [
            'field' => $this,
            'values' => $values,
        ])->render();
    }

    /**
     * Convert tab definition to array for API output
     *
     * @return array{
     *     id: string,
     *     label: string,
     *     description: string|null,
     *     position: string,
     *     selected: bool,
     *     fields: array<int, array{kind: 'group'|'custom_field', data: mixed}>
     * }
     */
    public function toArray(): array
    {
        $fields = [];

        foreach ($this->fields ?? [] as $item) {
            if ($item instanceof Group) {
                $fields[] = [
                    'kind' => 'group',
                    'data' => $item->toArray(),
                ];
            } else {
                // assume CustomField instance or any object with array-like public props
                $data = null;

                if (method_exists($item, 'toArray')) {
                    $data = $item->toArray();
                } else {
                    $data = [
                        'label' => $item->label ?? null,
                        'params' => $item->params ?? [],
                        'validation' => $item->validation ?? null,
                    ];
                }

                $fields[] = [
                    'kind' => 'custom_field',
                    'data' => $data,
                ];
            }
        }

        return [
            'id' => $this->id,
            'label' => $this->label,
            'description' => $this->description,
            'position' => $this->position,
            'selected' => $this->selected,
            'fields' => $fields,
        ];
    }

    /**
     * Get all custom fields inside this tab (direct or inside groups).
     *
     * @return array
     */
    public function getCustomFields(): array
    {
        $customFields = [];

        foreach ($this->fields as $field) {
            if ($field instanceof Group) {
                $customFields = array_merge($customFields, $field->customFields);
            } else {
                $customFields[] = $field;
            }
        }

        return $customFields;
    }
}
