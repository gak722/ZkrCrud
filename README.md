ZKRCrud - Laravel RESTful API Generator
🚀 A Laravel package for rapid API development with built-in CRUD, query filtering, validation, and authorization.

Table of Contents
Features

Installation

Basic Usage

API Endpoints

Query Parameters

Validation & Authorization

Hooks

Error Handling

Configuration

Examples

Contributing

License

Features
✔ Auto-generated CRUD endpoints
✔ Built-in Policy-based authorization
✔ Advanced filtering, sorting & includes (Spatie Query Builder)
✔ Request validation (custom rules or FormRequest support)
✔ Pre/Post operation hooks
✔ Pagination & field selection
✔ Consistent error handling

Installation
Install via Composer:

bash
composer require larapi/zkrcrud
Basic Usage
1. Create a Controller
Extend ZkrController and configure your model:

php
<?php

namespace App\Http\Controllers;

use Larapi\Zkrcrud\Http\Controllers\ZkrController;
use App\Models\Post;

class PostController extends ZkrController
{
    protected $model = Post::class;
    
    protected $allowedIncludes = ['author', 'comments'];
    protected $allowedFilters = ['title', 'status'];
    protected $allowedSorts = ['created_at', 'title'];
    protected $allowedFields = ['id', 'title', 'content'];
    
    // Optional: Custom validation rules
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }
    
    // Optional: Use a custom FormRequest
    protected ?string $requestClass = \App\Http\Requests\PostRequest::class;
}
2. Define Routes
php
use App\Http\Controllers\PostController;

Route::apiResource('posts', PostController::class);
API Endpoints
Method	Endpoint	Description
GET	/posts	List all posts (paginated)
GET	/posts/{id}	Get a single post
POST	/posts	Create a new post
PUT	/posts/{id}	Update a post
DELETE	/posts/{id}	Delete a post
Query Parameters
1. Filtering
http
GET /posts?filter[status]=published&filter[author_id]=1
2. Including Relationships
http
GET /posts?include=author,comments
3. Sorting
http
GET /posts?sort=-created_at,title  # `-` for descending
4. Field Selection
http
GET /posts?fields[posts]=id,title
5. Pagination
http
GET /posts?per_page=20
Validation & Authorization
1. Validation
Use rules() method for basic validation:

php
protected function rules(): array
{
    return [
        'title' => 'required|string|max:255',
    ];
}
Or use a custom FormRequest:

php
protected ?string $requestClass = \App\Http\Requests\PostRequest::class;
2. Authorization (Policies)
Automatically checks for:

viewAny → index()

view → show()

create → store()

update → update()

delete → destroy()

Example Policy:

php
public function update(User $user, Post $post)
{
    return $user->id === $post->user_id;
}
Hooks
Override these methods for custom logic:

php
protected function beforeStore(Request $request)
{
    // Runs before creating a record
}

protected function afterStore($model, Request $request)
{
    // Runs after creating a record
}

protected function beforeUpdate($model, Request $request)
{
    // Runs before updating a record
}

protected function afterUpdate($model, Request $request)
{
    // Runs after updating a record
}

protected function beforeDestroy($model)
{
    // Runs before deleting a record
}

protected function afterDestroy($model)
{
    // Runs after deleting a record
}
Error Handling
Status Code	Description
200 OK	Successful GET request
201 Created	Resource created
204 No Content	Resource deleted
401 Unauthorized	Missing/invalid token
403 Forbidden	Policy denied the action
404 Not Found	Resource not found
422 Unprocessable Entity	Validation failed
500 Internal Server Error	Server error
Example Error Response:

json
{
    "message": "Invalid query parameters",
    "errors": {
        "filter": ["Invalid filter: invalid_field"]
    }
}
Configuration
Property	Description	Example
$model	Required model class	Post::class
$requestClass	Custom FormRequest	PostRequest::class
$allowedIncludes	Allowed relationships	['author', 'comments']
$allowedFilters	Allowed filter fields	['status', 'author_id']
$allowedSorts	Allowed sort fields	['created_at', 'title']
$allowedFields	Allowed selected fields	['id', 'title']
Examples
1. Creating a Post
Request:

http
POST /posts
Content-Type: application/json

{
    "title": "Hello World",
    "content": "This is a test post."
}
Response (201 Created):

json
{
    "data": {
        "id": 1,
        "title": "Hello World",
        "content": "This is a test post.",
        "created_at": "2025-01-01T00:00:00.000000Z"
    }
}
2. Filtering & Sorting
Request:

http
GET /posts?filter[status]=published&sort=-created_at&include=author
Response (200 OK):

json
{
    "data": [
        {
            "id": 1,
            "title": "Latest Post",
            "status": "published",
            "author": {
                "id": 1,
                "name": "John Doe"
            }
        }
    ],
    "links": {
        "first": "/posts?page=1",
        "last": "/posts?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 1
    }
}
Contributing
Pull requests are welcome! For major changes, open an issue first.

License
MIT

🚀 Happy Coding! 🚀