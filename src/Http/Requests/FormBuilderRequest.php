<?php

namespace JobMetric\Form\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;
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
     * @param bool $includeHiddenFields
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

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Ensure that a Form instance is available, built from the FormBuilder.
     *
     * @return Form
     * @throws InvalidArgumentException
     *
     */
    protected function ensureForm(): Form
    {
        if (! $this->formBuilder) {
            throw new InvalidArgumentException('FormBuilder instance is required. Call setFormBuilder() before validation.');
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
            $name = $customField->params['name'] ?? null;

            if (! $name) {
                continue;
            }

            $attributes[$name] = trans($customField->label);
        }

        return $attributes;
    }

    /**
     * Build the validation messages array from CustomField definitions.
     *
     * Looks for a messages map on each field (either `$customField->messages`
     * or `$customField->params['messages']`). Keys may be either rule names
     * (e.g. `required`) which will be prefixed with the field name, or full
     * keys already in the `field.rule` format.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [];

        $form = $this->ensureForm();

        foreach ($form->getAllCustomFields($this->includeHiddenFields) as $customField) {
            $name = $customField->params['name'] ?? null;

            if (! $name) {
                continue;
            }

            $fieldMessages = $customField->messages ?? ($customField->params['messages'] ?? null);

            if (is_array($fieldMessages)) {
                foreach ($fieldMessages as $key => $value) {
                    if (! is_string($value)) {
                        continue;
                    }

                    if (! is_string($key)) {
                        continue;
                    }

                    $keyStr = $key;

                    // If key already starts with the field name, assume it's a fully-qualified
                    // message key (e.g. "field.rule" or "items.*.field.rule").
                    // Otherwise, scope the rule (and any variants like "min.string") to this field.
                    $fullKey = str_starts_with($keyStr, $name . '.') ? $keyStr : $name . '.' . $keyStr;

                    $messages[$fullKey] = $value;
                }
            }
        }

        return $messages;
    }
}
