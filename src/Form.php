<?php

namespace JobMetric\Form;

use JobMetric\CustomField\CustomField;
use JobMetric\Form\Group\Group;
use JobMetric\Form\Tab\Tab;
use Throwable;

class Form
{
    /**
     * Attribute action
     *
     * @var string|null $action
     */
    public ?string $action = null;

    /**
     * Attribute method
     *
     * @var string $method
     */
    public string $method = 'POST';

    /**
     * Attribute name
     *
     * @var string $name
     */
    public string $name = '';

    /**
     * Attribute enctype
     *
     * @var string $enctype
     */
    public string $enctype = 'application/x-www-form-urlencoded';

    /**
     * Attribute autocomplete
     *
     * @var bool $autocomplete
     */
    public bool $autocomplete = false;

    /**
     * Attribute target
     *
     * @var string $target
     */
    public string $target = '_self';

    /**
     * Attribute novalidate
     *
     * @var bool $novalidate
     */
    public bool $novalidate = false;

    /**
     * Attribute class
     *
     * @var string $class
     */
    public string $class = 'form d-flex flex-column flex-lg-row';

    /**
     * Attribute id
     *
     * @var string $id
     */
    public string $id = 'form';

    /**
     * Attribute csrf
     *
     * @var bool $csrf
     */
    public bool $csrf = true;

    /**
     * Attribute hidden custom field
     *
     * @var CustomField[] $hiddenCustomField
     */
    public array $hiddenCustomField;

    /**
     * Tabs within the form
     *
     * @var Tab[] $tabs
     */
    public array $tabs = [];

    public function __construct(
        ?string $action = null,
        string  $method = 'POST',
        string  $name = '',
        string  $enctype = 'application/x-www-form-urlencoded',
        bool    $autocomplete = false,
        string  $target = '_self',
        bool    $novalidate = false,
        string  $class = 'form d-flex flex-column flex-lg-row',
        string  $id = 'form',
        bool    $csrf = true,
        array   $hiddenCustomField = [],
        array   $tabs = []
    )
    {
        $this->action = $action;
        $this->method = $method;
        $this->name = $name;
        $this->enctype = $enctype;
        $this->autocomplete = $autocomplete;
        $this->target = $target;
        $this->novalidate = $novalidate;
        $this->class = $class;
        $this->id = $id;
        $this->csrf = $csrf;
        $this->hiddenCustomField = $hiddenCustomField;
        $this->tabs = $tabs;
    }

    /**
     * Get All Custom Fields
     *
     * @return CustomField[]
     */
    public function getAllCustomFields(): array
    {
        $customFields = [];

        foreach ($this->tabs as $tab) {
            foreach ($tab->fields as $field) {
                if ($field instanceof Group) {
                    foreach ($field->customFields as $groupCustomField) {
                        $customFields[] = $groupCustomField;
                    }
                }

                if ($field instanceof CustomField) {
                    $customFields[] = $field;
                }
            }
        }

        return $customFields;
    }

    /**
     * Render the form as HTML
     *
     * @param array $values
     *
     * @return string
     * @throws Throwable
     */
    public function toHtml(array $values = []): string
    {
        return view('form::form', [
            'field' => $this,
            'values' => $values,
        ])->render();
    }

    /**
     * Convert form definition to array for API output
     *
     * @return array
     */
    public function toArray(): array
    {
        $hiddenCustomField = array_map(function (CustomField $field) {
            return [
                'label' => $field->label ?? null,
                'params' => $field->params ?? [],
                'validation' => $field->validation ?? null,
            ];
        }, $this->hiddenCustomField ?? []);

        $tabs = array_map(function (Tab $tab) {
            return $tab->toArray();
        }, $this->tabs ?? []);

        return [
            'action' => $this->action,
            'method' => $this->method,
            'name' => $this->name,
            'enctype' => $this->enctype,
            'autocomplete' => $this->autocomplete,
            'target' => $this->target,
            'novalidate' => $this->novalidate,
            'class' => $this->class,
            'id' => $this->id,
            'csrf' => $this->csrf,
            'hiddenCustomField' => $hiddenCustomField,
            'tabs' => $tabs,
        ];
    }
}
