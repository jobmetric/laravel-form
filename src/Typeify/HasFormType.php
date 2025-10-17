<?php

namespace JobMetric\Form\Typeify;

use Closure;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use JobMetric\CustomField\CustomField;
use JobMetric\Form\Form;
use JobMetric\Form\FormBuilder;

/**
 * Trait HasFormType
 *
 * @package JobMetric\Form
 */
trait HasFormType
{
    /**
     * The form instances per type key.
     *
     * @var array<string, Form> $form
     */
    protected array $form = [];

    /**
     * Set Form.
     *
     * @param Closure<FormBuilder>|array $callable
     *
     * @return static
     */
    public function form(Closure|array $callable): static
    {
        if (isset($this->form[$this->type])) {
            throw new InvalidArgumentException('Form already exists for this type.');
        }

        if ($callable instanceof Closure) {
            $callable($builder = new FormBuilder);

            $this->form[$this->type] = $builder->build();
        } else {
            foreach ($callable as $form) {
                $builder = new FormBuilder;

                $builder->action($form['action']);
                $builder->method($form['method'] ?? 'POST');
                $builder->name($form['name'] ?? '');
                $builder->enctype($form['enctype'] ?? 'application/x-www-form-urlencoded');

                if (isset($form['autocomplete']) && $form['autocomplete'] === true) {
                    $builder->autocomplete();
                }

                $builder->target($form['target'] ?? '_self');

                if (isset($form['novalidate']) && $form['novalidate'] === true) {
                    $builder->novalidate();
                }

                $builder->class($form['class'] ?? 'form d-flex flex-column flex-lg-row');
                $builder->id($form['id'] ?? 'form');

                if (isset($form['removeCsrf']) && $form['removeCsrf'] === true) {
                    $builder->removeCsrf();
                }

                if (isset($form['hiddenCustomFields'])) {
                    foreach ($form['hiddenCustomFields'] as $hiddenCustomField) {
                        $builder->hiddenCustomField($hiddenCustomField);
                    }
                }

                if (isset($form['tabs'])) {
                    foreach ($form['tabs'] as $tab) {
                        $builder->tab($tab);
                    }
                }

                $this->form[$this->type] = $builder->build();
            }
        }

        $this->setTypeParam('form', $this->form);

        return $this;
    }

    /**
     * Get form.
     *
     * @return Form|null
     */
    public function getForm(): ?Form
    {
        $form = $this->getTypeParam('form', []);

        return $form[$this->type] ?? null;
    }

    /**
     * Get form custom fields.
     *
     * @return Collection<int, CustomField>
     */
    public function getFormCustomFields(): Collection
    {
        $form = $this->getTypeParam('form', []);

        $data = collect();

        if (isset($form[$this->type])) {
            return collect($form[$this->type]->getAllCustomFields());
        }

        return $data;
    }
}
