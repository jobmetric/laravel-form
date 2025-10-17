# Validation & Attributes (Requests)

Back To: [Documentation Index](../README.md#documentation)

Trait: `JobMetric\\Form\\Http\\Requests\\FormTypeObjectRequest`

Helpers:

- `renderFormFiled(array &$rules, ?Form $form): void`
  - Populates validation rules for each custom field (by its `params['name']`).
- `renderFormAttribute(array &$params, ?Form $form): void`
  - Populates a human-readable attribute map for your validator error messages.

Example (inside a Form Request):

```php
use JobMetric\Form\Http\Requests\FormTypeObjectRequest;
use JobMetric\Form\Typeify\HasFormType; // if you manage form per-type

class StoreArticleRequest extends FormRequest
{
    use FormTypeObjectRequest;

    public function rules(): array
    {
        $form = app(ArticleTypeProvider::class)->getForm(); // however you retrieve it

        $rules = [];
        $this->renderFormFiled($rules, $form);

        return $rules;
    }

    public function attributes(): array
    {
        $form = app(ArticleTypeProvider::class)->getForm();

        $attributes = [];
        $this->renderFormAttribute($attributes, $form);

        return $attributes;
    }
}
```

Note: Ensure each field defines a `name` in `params` so these helpers can resolve it.

With HasFormType:

```php
use JobMetric\Form\Typeify\HasFormType;
use JobMetric\Form\FormBuilder;
use JobMetric\CustomField\CustomFieldBuilder;

class ArticleType
{
    use HasFormType;

    protected string $type = 'article';

    public function __construct()
    {
        $this->form(function (FormBuilder $f) {
            $f->tab(function ($tab) {
                $tab->id('general')->label('General')->selected()->startPosition()
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('title')->label('Title')->validation('required|string');
                    });
            });
        });
    }
}
```

Next To: [Advanced Examples](examples.md)
