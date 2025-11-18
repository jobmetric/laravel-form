<?php

namespace JobMetric\Form\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;
use JobMetric\CustomField\CustomField;
use JobMetric\Form\Form;
use JobMetric\Form\FormBuilder;

/**
 * FormBuilderRequest
 *
 * Generic FormRequest that builds validation rules and attributes
 * from a FormBuilder/Form definition using CustomField instances.
 * It allows you to drive validation directly from your form builder
 * configuration instead of manually writing rules in each request.
 */
class FormBuilderRequest extends FormRequest
{
    /**
     * The FormBuilder instance used to define and build the form.
     *
     * @var FormBuilder|null
     */
    protected ?FormBuilder $formBuilder = null;

    /**
     * Cached built Form instance created from the FormBuilder.
     *
     * @var Form|null
     */
    protected ?Form $builtForm = null;

    /**
     * Flag indicating whether hidden fields should be included
     * in the validation rules and attributes.
     *
     * @var bool
     */
    protected bool $includeHiddenFields = true;

    /**
     * FormBuilderRequest constructor.
     *
     * @param FormBuilder $formBuilder
     * @param bool        $includeHiddenFields
     */
    public function __construct(FormBuilder $formBuilder, bool $includeHiddenFields = true)
    {
        parent::__construct();

        $this->setFormBuilder($formBuilder);
        $this->includeHiddenFields($includeHiddenFields);
    }

    /**
     * Set the FormBuilder instance for this request.
     *
     * @param FormBuilder $formBuilder
     *
     * @return static
     */
    public function setFormBuilder(FormBuilder $formBuilder): static
    {
        $this->formBuilder = $formBuilder;
        $this->builtForm = null;

        return $this;
    }

    /**
     * Configure whether hidden fields should be included in validation.
     *
     * @param bool $include
     *
     * @return static
     */
    public function includeHiddenFields(bool $include = true): static
    {
        $this->includeHiddenFields = $include;

        return $this;
    }

    /**
     * Ensure that a Form instance is available, built from the FormBuilder.
     *
     * @throws InvalidArgumentException
     *
     * @return Form
     */
    protected function ensureForm(): Form
    {
        if (! $this->formBuilder) {
            throw new InvalidArgumentException(
                'FormBuilder instance is required. Call setFormBuilder() before validation.'
            );
        }

        if (! $this->builtForm) {
            $this->builtForm = $this->formBuilder->build();
        }

        return $this->builtForm;
    }

    /**
     * Build the validation rules array from the CustomField definitions.
     *
     * @return array<string, string|array>
     */
    public function rules(): array
    {
        $rules = [];

        $form = $this->ensureForm();

        foreach ($form->getAllCustomFields($this->includeHiddenFields) as $customField) {
            /** @var CustomField $customField */
            $name = $customField->params['name'] ?? null;

            if (! $name) {
                continue;
            }

            $rules[$name] = $customField->validation ?? 'string|nullable|sometimes';
        }

        return $rules;
    }

    /**
     * Build the validation attributes array from the CustomField definitions.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];

        $form = $this->ensureForm();

        foreach ($form->getAllCustomFields($this->includeHiddenFields) as $customField) {
            /** @var CustomField $customField */
            $name = $customField->params['name'] ?? null;

            if (! $name) {
                continue;
            }

            $attributes[$name] = trans($customField->label);
        }

        return $attributes;
    }
}
