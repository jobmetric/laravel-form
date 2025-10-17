# Laravel Form — Overview

Back To: [Documentation Index](../README.md)

Laravel Form is a builder-style package for defining complex, dynamic forms in code and rendering them to HTML or serializing them to an array for API usage.

Key features:

- Fluent builders for `Form`, `Tab`, and `Group`
- First-class integration with `jobmetric/laravel-custom-field`
- Render to HTML via `toHtml()` or get a transportable schema via `toArray()`
- Tabs with start/end alignment, selected state
- Groups with titles and descriptions
- Hidden custom fields
- Traits to attach a form to a “type” and generate request rules/attributes

Core classes:

- `JobMetric\Form\Form`
- `JobMetric\Form\FormBuilder`
- `JobMetric\Form\Tab\Tab` and `TabBuilder`
- `JobMetric\Form\Group\Group` and `GroupBuilder`
- `JobMetric\Form\Typeify\HasFormType` (attach a form to a type)
- `JobMetric\Form\Http\Requests\FormTypeObjectRequest` (fill rules/attributes)

Output methods:

- `toHtml(array $values = []) : string`
- `toArray() : array`


Next To: [Quick Start](quickstart.md)
