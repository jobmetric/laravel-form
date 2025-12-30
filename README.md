[contributors-shield]: https://img.shields.io/github/contributors/jobmetric/laravel-form.svg?style=for-the-badge
[contributors-url]: https://github.com/jobmetric/laravel-form/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/jobmetric/laravel-form.svg?style=for-the-badge&label=Fork
[forks-url]: https://github.com/jobmetric/laravel-form/network/members
[stars-shield]: https://img.shields.io/github/stars/jobmetric/laravel-form.svg?style=for-the-badge
[stars-url]: https://github.com/jobmetric/laravel-form/stargazers
[license-shield]: https://img.shields.io/github/license/jobmetric/laravel-form.svg?style=for-the-badge
[license-url]: https://github.com/jobmetric/laravel-form/blob/master/LICENCE.md
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-blue.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/majidmohammadian

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

# Laravel Form

**Build Forms. Programmatically and Beautifully.**

Laravel Form simplifies form creation and rendering in Laravel applications. Stop writing HTML manually and start building complex, structured forms programmatically with confidence. It provides a fluent builder API to create forms with tabs, groups, and custom fields—all through a clean, chainable interface. Perfect for building dynamic admin panels, configuration forms, and API-driven form builders. This is where powerful form building meets developer-friendly simplicity—giving you complete control over form structure without the complexity.

## Why Laravel Form?

### Fluent Builder API

Laravel Form provides a clean, chainable API for building forms. Organize fields into tabs and groups, set form attributes, and configure validation—all through a single fluent chain. No more scattered HTML or inconsistent form structures.

### Tab-Based Organization

Organize complex forms into logical tabs. Each tab can contain multiple groups, and each group can contain multiple fields. This hierarchical structure makes it easy to build sophisticated forms while keeping code organized and maintainable.

### Group-Based Field Organization

Group related fields together with labels and descriptions. Groups provide visual separation and logical organization, making forms easier to understand and fill out.

### Integration with Custom Fields

Built on top of Laravel Custom Field, you can use all available field types (text, select, radio, image, etc.) and create custom field types. The form builder seamlessly integrates with the custom field system.

## What is Form Building?

Form building is the process of programmatically creating, configuring, and rendering HTML forms. Traditional approaches often involve:

- Writing HTML manually (error-prone, inconsistent)
- Using form builders with limited flexibility
- Creating custom components (time-consuming)
- Managing validation separately from form structure

Laravel Form solves these challenges by providing:

- **Fluent API**: Chain methods to build forms
- **Structured Organization**: Tabs and groups for complex forms
- **Type Safety**: Strongly-typed form classes
- **Consistent Output**: Standardized HTML structure
- **Automatic Validation**: Generate rules from form definition
- **Extensibility**: Easy to extend and customize

Consider a dynamic form builder where administrators can create forms with different structures, tabs, and field types. With Laravel Form, you can build forms programmatically, render them consistently, and serialize them for storage or API responses. The power of form building lies not only in flexible form creation but also in making it easy to extend, customize, and maintain throughout your application.

## What Awaits You?

By adopting Laravel Form, you will:

- **Build complex forms** - Organize forms with tabs and groups programmatically
- **Simplify form rendering** - Consistent HTML output with automatic CSRF tokens
- **Improve maintainability** - Fluent API reduces code complexity
- **Enable automatic validation** - Generate validation rules from form definitions
- **Integrate seamlessly** - Works perfectly with Laravel Custom Field
- **Maintain clean code** - Simple, intuitive API that follows Laravel conventions

## Quick Start

Install Laravel Form via Composer:

```bash
composer require jobmetric/laravel-form
```

## Documentation

Ready to transform your Laravel applications? Our comprehensive documentation is your gateway to mastering Laravel Form:

**[📚 Read Full Documentation →](https://jobmetric.github.io/packages/laravel-form/)**

The documentation includes:

- **Getting Started** - Quick introduction and installation guide
- **FormBuilder** - Fluent API for building forms
- **Form** - Form class with tabs, groups, and fields
- **Tabs & Groups** - Organize fields into logical sections
- **Custom Fields Integration** - Use all field types from Laravel Custom Field
- **Validation Requests** - Automatic validation rule generation
- **HTML Rendering** - Render forms to HTML with a single call
- **Array Serialization** - Export form definitions for APIs
- **Real-World Examples** - See how it works in practice

## Contributing

Thank you for participating in `laravel-form`. A contribution guide can be found [here](CONTRIBUTING.md).

## License

The `laravel-form` is open-sourced software licensed under the MIT license. See [License File](LICENCE.md) for more information.
