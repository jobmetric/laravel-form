# Rendering to HTML

Back To: [Documentation Index](../README.md)

The `Form::toHtml(array $values = [])` method renders the full form.

Example with values:

```php
$values = [
    'title' => old('title', $article->title ?? ''),
    'keywords' => old('keywords', ''),
];

echo $form->toHtml($values);
```

Blade template uses `form::form`, `form::tab-link`, `form::tab-data`, and `form::group` views under the hood.

Form attributes supported:

- `method` (theme-friendly POST/GET + method spoof)
- `action`
- `name`
- `enctype`
- `target`
- `autocomplete="on|off"`
- `novalidate`
- `class`
- `id`
- CSRF on by default (disable via `removeCsrf()`)

Method spoofing example:

```php
echo (new FormBuilder)
    ->action('/articles/1')
    ->method('PUT')
    ->build()
    ->toHtml();
```

Prefill values:

```php
echo $form->toHtml([
    'title' => 'Prefilled Title',
    'status' => 'published',
]);
```

Next To: [Serializing to Array](serialization.md)
