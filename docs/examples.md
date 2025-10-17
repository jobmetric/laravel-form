# Advanced Examples

Back To: [Documentation Index](../README.md)

Multiple Tabs, Groups, and Fields

```php
use JobMetric\Form\FormBuilder;
use JobMetric\CustomField\CustomFieldBuilder;
use JobMetric\CustomField\Option\OptionBuilder;

$form = (new FormBuilder)
    ->action('/save')
    ->method('PATCH')
    ->name('complex')
    ->enctype('multipart/form-data')
    ->autocomplete()
    ->target('_blank')
    ->novalidate()
    ->tab(function ($tab) {
        $tab->id('general')->label('General')->selected()->startPosition()
            ->group(function ($group) {
                $group->label('Info')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('title')->label('Title')->validation('required|string');
                    })
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('slug')->label('Slug');
                    });
            })
            ->group(function ($group) {
                $group->label('Meta')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::number()->name('order')->label('Order');
                    });
            });
    })
    ->tab(function ($tab) {
        $tab->id('details')->label('Details')->endPosition()
            ->group(function ($group) {
                $group->label('SEO')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::select()->name('status')->label('Status')->options(function (OptionBuilder $opt) {
                            $opt->label('Draft')->value('draft')->build();
                            $opt->label('Published')->value('published')->selected()->build();
                        });
                    })
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('keywords')->label('Keywords');
                    });
            })
            ->group(function ($group) {
                $group->label('Extra')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('note')->label('Note');
                    });
            });
    })
    ->build();

// HTML
echo $form->toHtml([ 'title' => 'Hello' ]);

// Array
$schema = $form->toArray();
```

Hidden Fields

```php
$form = (new FormBuilder)
    ->hiddenCustomField(function (CustomFieldBuilder $cf) {
        $cf::hidden()->name('_context')->value('editor');
    })
    ->build();
```

Next To: [Overview](overview.md)
