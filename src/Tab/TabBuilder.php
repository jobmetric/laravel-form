<?php

namespace JobMetric\Form\Tab;

use Closure;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\Group\GroupBuilder;

class TabBuilder
{
    use Macroable;

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
     * Fields within the tab
     *
     * @var array $fields
     */
    public array $fields = [];

    /**
     * set the label of the tab
     *
     * @param string $label
     *
     * @return static
     */
    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * set the description of the tab
     *
     * @param string $description
     *
     * @return static
     */
    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Add a group to the field tab
     *
     * @param Closure<GroupBuilder>|array $callable
     *
     * @return static
     */
    public function group(Closure|array $callable): static
    {
        if ($callable instanceof Closure) {
            $callable($builder = new GroupBuilder);

            $this->fields[] = $builder->build();
        } else {
            foreach ($callable as $group) {
                $builder = new GroupBuilder;

                $builder->label($group['label']);

                if (isset($group['description'])) {
                    $builder->description($group['description']);
                }

                if (isset($group['customFields'])) {
                    foreach ($group['customFields'] as $customField) {
                        $builder->customField($customField);
                    }
                }

                $this->fields[] = $builder->build();
            }
        }

        return $this;
    }

    /**
     * Add a custom field to the field tab
     *
     * @param Closure<CustomFieldBuilder> $callable
     *
     * @return static
     */
    public function customField(Closure $callable): static
    {
        $callable($builder = new CustomFieldBuilder);

        $this->fields[] = $builder->build();

        return $this;
    }

    /**
     * Build the tab
     *
     * @return Tab
     */
    public function build(): Tab
    {
        if (empty($this->label)) {
            throw new InvalidArgumentException('Tab label is required');
        }

        return new Tab($this->label, $this->description, $this->fields);
    }
}
