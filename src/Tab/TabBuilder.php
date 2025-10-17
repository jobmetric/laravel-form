<?php

namespace JobMetric\Form\Tab;

use Closure;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\Form\Group\GroupBuilder;
use Throwable;

class TabBuilder
{
    use Macroable;

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

    /**
     * set the id of the tab
     *
     * @param string $id
     *
     * @return static
     */
    public function id(string $id): static
    {
        $this->id = 'tab-' . $id;

        return $this;
    }

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
     * set the start position of the tab
     *
     * @return static
     */
    public function startPosition(): static
    {
        $this->position = 'start';

        return $this;
    }

    /**
     * set the end position of the tab
     *
     * @return static
     */
    public function endPosition(): static
    {
        $this->position = 'end';

        return $this;
    }

    /**
     * selected tab
     *
     * @return static
     */
    public function selected(): static
    {
        $this->selected = true;

        return $this;
    }

    /**
     * Add a group to the field tab
     *
     * @param Closure<GroupBuilder>|array $callable
     *
     * @return static
     * @throws Throwable
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
     * @throws Throwable
     */
    public function build(): Tab
    {
        if (empty($this->id)) {
            throw new InvalidArgumentException('Tab id is required');
        }

        if (empty($this->label)) {
            throw new InvalidArgumentException('Tab label is required');
        }

        return new Tab($this->id, $this->label, $this->description, $this->position, $this->selected, $this->fields);
    }
}
