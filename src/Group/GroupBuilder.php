<?php

namespace JobMetric\Form\Group;

use Closure;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JobMetric\CustomField\CustomField;
use JobMetric\CustomField\CustomFieldBuilder;
use Throwable;

class GroupBuilder
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
     * Custom field within the group
     *
     * @var CustomField[] $customFields
     */
    public array $customFields = [];

    /**
     * set the label of the group
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
     * set the description of the group
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
     * Add a custom field to the group
     *
     * @param Closure<CustomFieldBuilder> $callable
     *
     * @return static
     */
    public function customField(Closure $callable): static
    {
        $callable($builder = new CustomFieldBuilder);

        $this->customFields[] = $builder->build();

        return $this;
    }

    /**
     * Build the group
     *
     * @return Group
     * @throws Throwable
     */
    public function build(): Group
    {
        if (empty($this->label)) {
            throw new InvalidArgumentException('Group label is required');
        }

        return new Group($this->label, $this->description, $this->customFields);
    }

    /**
     * Get all custom fields added to this group.
     *
     * @return CustomField[]
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }
}
