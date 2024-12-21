<?php

namespace JobMetric\Form;

use Closure;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JobMetric\Form\Tab\Tab;
use JobMetric\Form\Tab\TabBuilder;

class FormBuilder
{
    use Macroable;

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

    /**
     * set the action of the form
     *
     * @param string $action
     *
     * @return static
     */
    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    /**
     * set the method of the form
     *
     * @param string $method
     *
     * @return static
     */
    public function method(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    /**
     * set the name of the form
     *
     * @param string $name
     *
     * @return static
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * set the enctype of the form
     *
     * @param string $enctype
     *
     * @return static
     */
    public function enctype(string $enctype): static
    {
        if (!in_array($enctype, ['application/x-www-form-urlencoded', 'multipart/form-data', 'text/plain'])) {
            throw new InvalidArgumentException('Invalid enctype');
        }

        $this->enctype = $enctype;

        return $this;
    }

    /**
     * set the autocomplete of the form
     *
     * @return static
     */
    public function autocomplete(): static
    {
        $this->autocomplete = true;

        return $this;
    }

    /**
     * set the target of the form
     *
     * @param string $target
     *
     * @return static
     */
    public function target(string $target): static
    {
        if (!in_array($target, ['_self', '_blank', '_parent', '_top'])) {
            throw new InvalidArgumentException('Invalid target');
        }

        $this->target = $target;

        return $this;
    }

    /**
     * set the novalidate of the form
     *
     * @return static
     */
    public function novalidate(): static
    {
        $this->novalidate = true;

        return $this;
    }

    /**
     * Add a tab to the form
     *
     * @param Closure<TabBuilder>|array $callable
     *
     * @return static
     */
    public function tab(Closure|array $callable): static
    {
        if ($callable instanceof Closure) {
            $callable($builder = new TabBuilder);

            $this->tabs[] = $builder->build();
        } else {
            foreach ($callable as $tab) {
                $builder = new TabBuilder;

                $builder->label($tab['label']);

                if (isset($tab['description'])) {
                    $builder->description($tab['description']);
                }

                if (isset($tab['groups'])) {
                    foreach ($tab['groups'] as $group) {
                        $builder->group($group);
                    }
                }

                if (isset($tab['customFields'])) {
                    foreach ($tab['customFields'] as $customField) {
                        $builder->customField($customField);
                    }
                }

                $this->tabs[] = $builder->build();
            }
        }

        return $this;
    }

    /**
     * Build the params of the form
     *
     * @return Form
     */
    public function build(): Form
    {
        return new Form(
            $this->action,
            $this->method,
            $this->name,
            $this->enctype,
            $this->autocomplete,
            $this->target,
            $this->novalidate,
            $this->tabs
        );
    }
}
