# Serializing to Array

Back To: [Documentation Index](../README.md#documentation)

Use `Form::toArray()` to get a transportable JSON-ready structure.

Shape:

```json
{
  "action": "...",
  "method": "POST|PUT|...",
  "name": "...",
  "enctype": "...",
  "autocomplete": true,
  "target": "_self|_blank",
  "novalidate": false,
  "class": "...",
  "id": "...",
  "csrf": true,
  "hiddenCustomField": [
    { "label": "...", "params": {"name": "..."}, "validation": "..." }
  ],
  "tabs": [
    {
      "id": "tab-...",
      "label": "...",
      "description": null,
      "position": "start|end",
      "selected": true,
      "fields": [
        { "kind": "group", "data": { "label": "...", "description": null, "customFields": [ ... ] } },
        { "kind": "custom_field", "data": { "label": "...", "params": {"name": "..."}, "validation": "..." } }
      ]
    }
  ]
}
```

This can be served via API to a frontend renderer, or stored as a snapshot of the current form configuration.

Example:

```php
return response()->json($form->toArray());
```

Next To: [Validation & Attributes](validation.md)
