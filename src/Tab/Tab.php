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
}
