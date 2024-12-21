<?php

namespace JobMetric\Form;

use Closure;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Trait FormServiceType
 *
 * @package JobMetric\Form
 */
trait FormServiceType
{
    /**
     * The form attributes.
     *
     * @var array $form
     */
    protected array $form = [];

    /**
     * Set Form.
     *
     * @param Closure<FormBuilder>|array $callable
     *
     * @return static
     * @throws Throwable
     */
    public function form(Closure|array $callable): static
    {
        if ($callable instanceof Closure) {
            $callable($builder = new FormBuilder);

            $this->form[$this->type][] = $builder->build();
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

                if (isset($form['tabs'])) {
                    foreach ($form['tabs'] as $tab) {
                        $builder->tab($tab);
                    }
                }

                $this->form[$this->type][] = $builder->build();
            }
        }

        $this->setTypeParam('form', $this->form);

        return $this;
    }

    /**
     * Get form.
     *
     * @return Collection
     */
    public function getForm(): Collection
    {
        $form = $this->getTypeParam('form', []);

        return collect($form[$this->type] ?? []);
    }
}
