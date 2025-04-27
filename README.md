# ZKRCrud - Laravel RESTful API Generator Package

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![License](https://img.shields.io/packagist/l/larapi/zkrcrud?style=for-the-badge)

ZKRCrud is a powerful Laravel package that **automates RESTful API development** with built-in CRUD operations, advanced filtering, and policy-based authorization. Perfect for rapid API development with Laravel 10+.

## ✨ Key Features

- 🚀 **Auto-generated CRUD endpoints**
- 🔒 **Policy-based authorization**
- 🔍 **Advanced query filtering** (Spatie Query Builder)
- ✅ **Automatic request validation**
- 📊 **Smart pagination**
- 🛡️ **Comprehensive error handling**
- 🔄 **Pre/post operation hooks**

## 📦 Installation

Install via Composer:

```bash
composer require larapi/zkrcrud

🚀 Quick Start
1. Create Controller
```
```php

<?php 
namespace App\Http\Controllers;

use Larapi\Zkrcrud\Http\Controllers\ZkrController;
use App\Models\Product;

class ProductController extends ZkrController
{
    protected $model = Product::class;
    
    protected $allowedIncludes = ['category', 'reviews'];
    protected $allowedFilters = ['name', 'price', 'in_stock'];
    protected $allowedSorts = ['created_at', 'price'];
    protected $allowedFields = ['id', 'name', 'price'];
    
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ];
    }
}
```
2. Define Routes

```php
<?php
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);
```

## 2. Define Routes

```php
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);
```

🔍 API Endpoints

Method | Endpoint | Description
GET | /products | Paginated product list
GET | /products/{id} | Get single product
POST | /products | Create new product
PUT | /products/{id} | Update product
DELETE | /products/{id} | Delete product

⚙️ Advanced Usage

Query Filtering

```http
GET /products?filter[price][gt]=100&include=category&sort=-created_at
```

Supported Parameters:

include – Load relationships

filter – Filter by fields

sort – Sort results (- prefix for DESC)

fields – Select specific fields

per_page – Items per page (pagination)

🛠️ Custom Hooks
Example usage of hooks inside the controller:

```php

protected function beforeStore(Request $request)
{
    // Logic before creation
    $request->merge(['created_by' => auth()->id()]);
}

protected function afterUpdate($model, Request $request)
{
    // Logic after update
    $model->history()->create($request->all());
}
```

🔒 Authorization
Automatically checks the following policy methods:


Action	Policy Method
List	viewAny
View	view
Create	create
Update	update
Delete	delete

🛠️ Configuration
Required:

```php
<?php
class YourController extends Controller{
protected $model = YourModel::class;
protected ?string $requestClass = CustomRequest::class;
protected array $allowedIncludes = [];
protected array $allowedFilters = [];
protected array $allowedSorts = [];
protected array $allowedFields = [];
/// rest of the code
}
```

Optional:
```php

protected ?string $requestClass = CustomRequest::class;
protected array $allowedIncludes = [];
protected array $allowedFilters = [];
protected array $allowedSorts = [];
protected array $allowedFields = [];
```
🛠️ System Requirements
PHP 8.1+

Laravel 10+

Composer 2.0+


🤝 Contributing
Pull requests are welcome!
Please follow PSR-12 coding standards.
