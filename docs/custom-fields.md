# Custom Fields Integration

Back To: [Documentation Index](../README.md)

This package relies on `jobmetric/laravel-custom-field` for input fields. Use `CustomFieldBuilder` methods to define fields inside groups or directly under tabs.

Common fields (non-exhaustive):

- `text()`, `number()`, `email()`, `password()`, `date()`, `select()`, `hidden()`, ...

Common chainable methods (varies by field):

- `name(string)` — required
- `label(string)` — UI label
- `validation(string|array)` — e.g., `required|string`
- `placeholder(string)`
- `required()`, `readonly()`, `disabled()`, `autofocus()`
- `class(string)`, `id(string)`

Select options example:

```php
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\CustomField\Option\OptionBuilder;

$group->customField(function (CustomFieldBuilder $cf) {
    $cf::select()->name('status')->label('Status')->options(function (OptionBuilder $opt) {
        $opt->label('Draft')->value('draft')->build();
        $opt->label('Published')->value('published')->selected()->build();
    });
});
```

Hidden custom field example:

```php
$builder->hiddenCustomField(function (CustomFieldBuilder $cf) {
    $cf::hidden()->name('_token_hidden')->value('...');
});
```

Radio/Checkbox example:

```php
$group->customField(function (CustomFieldBuilder $cf) {
    $cf::radio()->name('visibility')->label('Visibility')->options([
        ['label' => 'Public', 'value' => 'public', 'selected' => true],
        ['label' => 'Private', 'value' => 'private'],
    ]);
});
```

Next To: [Rendering to HTML](rendering.md)
