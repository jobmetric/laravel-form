<?php

namespace JobMetric\Form\Tab;

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
     * render the tab link as HTML
     *
     * @return string
     * @throws Throwable
     */
    public function renderLink(): string
    {
        return view('form::tab-link', [
            'field' => $this,
        ])->render();
    }

    /**
     * render the tab data as HTML
     *
     * @param array $values
     *
     * @return string
     * @throws Throwable
     */
    public function renderData(array $values = []): string
    {
        return view('form::tab-data', [
            'field' => $this,
            'values' => $values,
        ])->render();
    }

    /**
     * Convert tab definition to array for API output
     *
     * @return array
     */
    public function toArray(): array
    {
        $fields = [];

        foreach ($this->fields ?? [] as $item) {
            if ($item instanceof \JobMetric\Form\Group\Group) {
                $fields[] = [
                    'kind' => 'group',
                    'data' => $item->toArray(),
                ];
            } else {
                // assume CustomField instance
                $fields[] = [
                    'kind' => 'custom_field',
                    'data' => [
                        'label' => $item->label ?? null,
                        'params' => $item->params ?? [],
                        'validation' => $item->validation ?? null,
                    ],
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
}
