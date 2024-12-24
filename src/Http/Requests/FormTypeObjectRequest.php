<?php

namespace JobMetric\Form\Http\Requests;

use JobMetric\CustomField\CustomField;
use JobMetric\Form\Form;
use Throwable;

trait FormTypeObjectRequest
{
    /**
     * @throws Throwable
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
     * @throws Throwable
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
