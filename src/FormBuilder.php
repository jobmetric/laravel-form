<?php

namespace JobMetric\Form;

use Closure;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JobMetric\CustomField\CustomField;
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\Tab\Tab;
use JobMetric\Form\Tab\TabBuilder;

class FormBuilder
{
    use Macroable;

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
    public string $name = 'form';

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
     * The hidden custom field instance.
     *
     * @var CustomField[] $hiddenCustomField
     */
    protected array $hiddenCustomField = [];

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
     * set the class of the form
     *
     * @param string $class
     *
     * @return static
     */
    public function class(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    /**
     * set the id of the form
     *
     * @param string $id
     *
     * @return static
     */
    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * unset the csrf of the form
     *
     * @return static
     */
    public function removeCsrf(): static
    {
        $this->csrf = false;

        return $this;
    }

    /**
     * Add a hidden custom field to the form
     *
     * @param Closure<CustomFieldBuilder>|array $callable
     *
     * @return static
     */
    public function hiddenCustomField(Closure|array $callable): static
    {
        $callable($builder = new CustomFieldBuilder);

        $this->hiddenCustomField[] = $builder->build();

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
            $this->class,
            $this->id,
            $this->csrf,
            $this->hiddenCustomField,
            $this->tabs
        );
    }
}
