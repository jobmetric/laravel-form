# Tabs and Groups

Back To: [Documentation Index](../README.md)

Tab Builder: `JobMetric\\Form\\Tab\\TabBuilder`

- `id(string $id)` — will prefix with `tab-`
- `label(string $label)`
- `description(string $description)` (optional)
- `startPosition()` or `endPosition()`
- `selected()`
- `group(Closure $callable)` — adds a group to the tab
- `customField(Closure $callable)` — adds a field directly under the tab
- `build(): Tab`

Group Builder: `JobMetric\\Form\\Group\\GroupBuilder`

- `label(string $label)`
- `description(string $description)` (optional)
- `customField(Closure $callable)`
- `build(): Group`

Example

```php
use JobMetric\Form\FormBuilder;
use JobMetric\CustomField\CustomFieldBuilder;

$form = (new FormBuilder)
    ->tab(function ($tab) {
        $tab->id('general')->label('General')->selected()->startPosition()
            ->group(function ($group) {
                $group->label('Info')
                    ->description('Basic information')
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('title')->label('Title');
                    })
                    ->customField(function (CustomFieldBuilder $cf) {
                        $cf::text()->name('slug')->label('Slug');
                    });
            })
            ->customField(function (CustomFieldBuilder $cf) {
                $cf::text()->name('subtitle')->label('Subtitle');
            });
    })
    ->build();
```

More examples

```php
// Direct custom field on tab (without group)
$form = (new FormBuilder)
    ->tab(function ($tab) {
        $tab->id('seo')->label('SEO')->endPosition()
            ->customField(function (CustomFieldBuilder $cf) {
                $cf::text()->name('keywords')->label('Keywords');
            });
    })
    ->build();
```

Next To: [Custom Fields Integration](custom-fields.md)
