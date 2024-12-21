<?php

namespace JobMetric\Form;

use JobMetric\Form\Tab\Tab;

class Form
{
    /**
     * Attribute action
     *
     * @var string $action
     */
    public string $action;

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
     * Tabs within the form
     *
     * @var Tab[] $tabs
     */
    public array $tabs = [];

    public function __construct(
        string $action,
        string $method = 'POST',
        string $name = '',
        string $enctype = 'application/x-www-form-urlencoded',
        bool   $autocomplete = false,
        string $target = '_self',
        bool   $novalidate = false,
        array  $tabs = []
    )
    {
        $this->action = $action;
        $this->method = $method;
        $this->name = $name;
        $this->enctype = $enctype;
        $this->autocomplete = $autocomplete;
        $this->target = $target;
        $this->novalidate = $novalidate;
        $this->tabs = $tabs;
    }
}
