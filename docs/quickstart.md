# Quick Start

Back To: [Documentation Index](../README.md#documentation)

Define and render a simple form with two tabs and a group:

```php
use JobMetric\Form\FormBuilder;
use JobMetric\CustomField\CustomFieldBuilder;

$form = (new FormBuilder)
    ->action('/articles')
    ->method('POST')
    ->name('article-form')
    ->tab(function ($tab) {
        $tab->id('general')
            ->label('General')
            ->selected()
            ->startPosition()
            ->group(function ($group) {
                $group->label('Information')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('title')->label('Title')->validation('required|string');
                    });
            });
    })
    ->tab(function ($tab) {
        $tab->id('seo')
            ->label('SEO')
            ->endPosition()
            ->group(function ($group) {
                $group->label('Meta')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('keywords')->label('Keywords');
                    });
            });
    })
    ->build();

// Render to HTML
echo $form->toHtml();

// Or export to array (for API schema)
return response()->json($form->toArray());
```

In Blade you can pass values for pre-filling:

```php
{!! $form->toHtml(['title' => old('title', $article->title ?? '')]) !!}
```

Additional examples:

- Hidden CSRF removal and method spoofing

```php
$form = (new FormBuilder)
    ->action('/articles/1')
    ->method('PUT')
    ->removeCsrf()
    ->build();

echo $form->toHtml(); // includes @method('PUT')
```

Next To: [Form Builder API](form-builder.md)
