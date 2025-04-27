# Gufran Laravel Package

Gufran is a Laravel package designed to simplify the creation of RESTful APIs, similar to the Orion package. It provides a streamlined way to handle CRUD operations, relationships, and query parameters with minimal boilerplate code.

## Features

- Simplified CRUD operations for Eloquent models.
- Support for complex query parameters (filters, sorting, pagination).
- Relationship handling for nested resources.
- Extensible and customizable controllers.

## Installation

To install the package, use Composer:

```bash
composer require gufran/gufran
```

## Configuration

After installation, publish the configuration file (if applicable):

```bash
php artisan vendor:publish --tag=gufran-config
```

## Usage

### Basic CRUD Controller

To create a basic CRUD controller for your model, extend the `Gufran\Http\Controllers\ApiController`:

```php
namespace App\Http\Controllers;

use Gufran\Http\Controllers\ApiController;

class PostController extends ApiController
{
    protected $model = \App\Models\Post::class;
}
```

### Advanced Features

#### Filtering and Sorting

Use query parameters to filter and sort results:

- `?filter[column]=value` - Filter by column.
- `?sort=column` - Sort by column (use `-column` for descending order).

#### Nested Relationships

Easily handle nested relationships by defining them in your controller.

```php
protected $relations = ['comments', 'author'];
```

#### Customizing Behavior

Override methods in your controller to customize behavior:

```php
protected function beforeStore($request)
{
    // Custom logic before storing a resource
}
```

## Testing

Run the package tests using PHPUnit:

```bash
vendor/bin/phpunit
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any bugs or feature requests.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
