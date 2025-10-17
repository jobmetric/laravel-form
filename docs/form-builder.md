# Form Builder API

Back To: [Documentation Index](../README.md)

Namespace: `JobMetric\\Form`

Builder: `FormBuilder`

Fluent setters

- `action(string $action)`
- `method(string $method)` — e.g., `GET`, `POST`, `PUT`, `PATCH`, `DELETE` (non-GET/POST are spoofed)
- `name(string $name)`
- `enctype(string $enctype)` — e.g., `multipart/form-data`
- `autocomplete()` — enables autocomplete (on); off by default
- `target(string $target)` — e.g., `_self`, `_blank`
- `novalidate()` — adds the `novalidate` attribute
- `class(string $class)`
- `id(string $id)`
- `removeCsrf()` — disables `@csrf` token output

Collections

- `hiddenCustomField(Closure $callable)` — adds a hidden custom field
  - Example:
    ```php
    $builder->hiddenCustomField(function (CustomFieldBuilder $cf) {
        $cf::hidden()->name('_token_hidden')->value('...');
    });
    ```

- `tab(Closure $callable)` — adds a tab; see Tab Builder below

Build

- `build(): Form` — returns an immutable `Form` instance

Output

- `Form::toHtml(array $values = []): string`
- `Form::toArray(): array`

Notes

- Current version focuses on closure-based API; array-based shorthands for fields are not fully supported across all builders.

Examples

```php
// Full attribute coverage
$form = (new FormBuilder)
    ->action('/submit')
    ->method('PATCH')
    ->name('my-form')
    ->enctype('multipart/form-data')
    ->autocomplete()
    ->target('_blank')
    ->novalidate()
    ->class('form custom')
    ->id('form-id')
    ->build();
```

```php
// Hidden custom field
$form = (new FormBuilder)
    ->hiddenCustomField(function (CustomFieldBuilder $cf) {
        $cf::hidden()->name('_token_hidden')->value('abc');
    })
    ->build();
```

Next To: [Tabs & Groups](tabs-groups.md)
