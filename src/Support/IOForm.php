<?php

namespace JobMetric\Form\Support;

use Illuminate\Support\Arr;
use JobMetric\CustomField\CustomField;
use JobMetric\Form\Form;
use JobMetric\Form\FormBuilder;

/**
 * Lightweight helper to normalise payloads according to the form definition.
 */
class IOForm
{
    /**
     * Ensure we always work with a concrete Form instance.
     */
    protected static function resolveForm(FormBuilder|Form $form): Form
    {
        return $form instanceof FormBuilder ? $form->build() : $form;
    }

    /**
     * Get all field names defined on the form (respecting hidden flag).
     *
     * @return string[]
     */
    protected static function fieldNames(Form $form, bool $includeHidden): array
    {
        $fields = $form->getAllCustomFields($includeHidden);

        return array_values(
            array_filter(
                array_map(
                    static function (CustomField $customField) {
                        return $customField->params['name'] ?? null;
                    },
                    $fields
                )
            )
        );
    }

    /**
     * Core normalization: keep only form-defined keys.
     *
     * @param  FormBuilder|Form    $form
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public static function normalize(FormBuilder|Form $form, array $data, bool $includeHidden = true): array
    {
        $formInstance = static::resolveForm($form);
        $keys = static::fieldNames($formInstance, $includeHidden);

        $normalized = Arr::only($data, $keys);

        // Future casting hooks can be added here per custom field definition.
        return $normalized;
    }

    /**
     * Final payload for storing (e.g. JSON column).
     *
     * @param  FormBuilder|Form    $form
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public static function forStore(FormBuilder|Form $form, array $data, bool $includeHidden = true): array
    {
        return static::normalize($form, $data, $includeHidden);
    }

    /**
     * Render HTML form using normalized values.
     *
     * @param  FormBuilder|Form    $form
     * @param  array<string,mixed> $values
     */
    public static function toHtml(FormBuilder|Form $form, array $values = [], bool $includeHidden = true): string
    {
        $formInstance = static::resolveForm($form);
        $normalized = static::normalize($formInstance, $values, $includeHidden);

        return $formInstance->toHtml($normalized);
    }

    /**
     * Normalized values as plain array.
     *
     * @param  FormBuilder|Form    $form
     * @param  array<string,mixed> $values
     * @return array<string,mixed>
     */
    public static function toArray(FormBuilder|Form $form, array $values = [], bool $includeHidden = true): array
    {
        return static::normalize($form, $values, $includeHidden);
    }
}
