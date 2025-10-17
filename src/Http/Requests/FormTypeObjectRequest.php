<?php

namespace JobMetric\Form\Http\Requests;

use JobMetric\CustomField\CustomField;
use JobMetric\Form\Form;
use Throwable;

trait FormTypeObjectRequest
{
    /**
     * Populate validation rules base on form custom fields
     *
     * @param array<string, mixed> $rules Reference to rules array to be filled
     * @param Form|null $form The form instance
     *
     * @return void
     */
    public function renderFormFiled(array &$rules, Form|null $form): void
    {
        if (!$form) {
            return;
        }

        foreach ($form->getAllCustomFields() as $customField) {
            /**
             * @var CustomField $customField
             */
            $name = $customField->params['name'] ?? null;

            $rules[$name] = $customField->validation ?? 'string|nullable|sometimes';
        }
    }

    /**
     * Populate attribute names based on form custom fields
     *
     * @param array<string, string> $params Reference to attributes map to be filled
     * @param Form|null $form The form instance
     *
     * @return void
     */
    public function renderFormAttribute(array &$params, Form|null $form): void
    {
        if (!$form) {
            return;
        }

        foreach ($form->getAllCustomFields() as $customField) {
            /**
             * @var CustomField $customField
             */
            $name = $customField->params['name'];

            $params[$name] = trans($customField->label);
        }
    }
}
