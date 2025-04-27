<?php

namespace Larapi\Zkrcrud\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

abstract class ZkrController extends Controller
{
    use \Larapi\Zkrcrud\Traits\ApiResponse;
    use \Larapi\Zkrcrud\Helpers\ExceptionHandler;
    use AuthorizesRequests;
    protected  $model;
    protected ?string $requestClass = null;

    protected  $allowedIncludes = [];
    protected  $allowedFilters = [];
    protected  $allowedSorts = [];
    protected  $allowedFields = [];

    protected function getModelInstance(): Model
    {
        return new $this->model;
    }

    public function index(Request $request)
    {
        try {
            $this->authorizeResource('viewAny', $this->getModelInstance());
            $result= $this->validateRequest($request);
            if ($result['success'] === false) {
                return $this->errorResponse($result['message'] ,422,$result['errors']);
            }
            $query = QueryBuilder::for($this->getModelInstance()::class)
                       ->allowedFields($this->allowedFields)
                       ->allowedIncludes($this->allowedIncludes)
                       ->allowedFilters($this->allowedFilters)
                       ->allowedSorts($this->allowedSorts)
                       ;
              

            $perPage = $request->input('per_page', 15);
            return $this->paginatedResponse(
                $query->paginate($perPage),
                200
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function show(Request $request, $id)
    {
        try {

            $model = $this->getModelInstance()->findOrFail($id);

            $this->authorizeResource('view', $model);

            $result=  $this->validateRequest($request);
            if ($result['success'] === false) {
                return $this->errorResponse($result['message'] ,422,$result['errors']);
            }
            $query = QueryBuilder::for($this->getModelInstance())
                ->allowedFields($this->allowedFields)
                ->allowedIncludes($this->allowedIncludes)
                ->whereKey($id);

            try {
                $record = $query->firstOrFail();
                return $this->successResponse($record, 200);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return $this->handleException($e);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorizeResource('create', $this->getModelInstance());
            $validated = $this->validateRequestOrDefault($request);
            $this->beforeStore($request);
            $model = $this->getModelInstance()->create($validated);
            $this->afterStore($model, $request);
            return $this->successResponse($model, 201);
        } 
        catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $model = $this->getModelInstance()->findOrFail($id);
            $this->authorizeResource('update', $model);
            $validated = $this->validateRequestOrDefault($request);
            $this->beforeUpdate($model, $request);
            $model->update($validated);
            $this->afterUpdate($model, $request);
            $this->successResponse($model, 200);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $model = $this->getModelInstance()->findOrFail($id);
            $this->authorizeResource('delete', $model);
            $this->beforeDestroy($model);
            $model->delete();
            $this->afterDestroy($model);
            return $this->successResponse(null, 204);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
 // validate index and show request
    protected  function validateRequest(Request $request): array
    {
        $errors = [];

        // Validate includes
        $includes = explode(',', (string) $request->query('include'));
        foreach ($includes as $include) {
            if (!empty($include) && !in_array($include, $this->allowedIncludes)) {
                $errors['include'][] = "Invalid include: {$include}";
            }
        }

        // Validate filters
        if ($request->has('filter') && !empty($this->allowedFilters)) {
            if(!empty($request->query('filter')))
            foreach (array_keys($request->query('filter')) as $key) {
                
                if (!in_array($key, $this->allowedFilters)) {
                    $errors['filter'][] = "Invalid filter: {$key}";
                }
            }
        }

        // Validate sorts
        $sorts = explode(',', (string) $request->query('sort'));
        foreach ($sorts as $sortField) {
            $field = ltrim($sortField, '-'); // Support for descending `-created_at`
            if (!empty($field)  && !in_array($field, $this->allowedSorts)) {
                $errors['sort'][] = "Invalid sort field: {$field}";
            }
        }

        if (!empty($errors)) {
            // abort(422, json_encode([
            //     'message' => 'Invalid query parameters',
            //     'errors' => $errors,
            // ]));
           return [
                'message' => 'Invalid query parameters',
                'errors' => $errors,
                'success' => false,
            ];
        }
        return ['success' => true, 'errors' => $errors];
    }
    protected function validateRequestOrDefault(Request $request): array
    {
        if ($this->requestClass && class_exists($this->requestClass)) {
            return app($this->requestClass)->validated();
        }
        $rules = $this->rules();

    // If no validation rules are defined, skip validation and allow creation
    if (empty($rules)) {
        return $request->all(); // Allow the request data to pass through without validation
    }

    // Otherwise, perform standard validation based on the rules
    return $request->validate($rules);
    }
    protected function rules(): array
    {
        return [];
    }
    protected function beforeStore(Request $request)
    {
        // Default hook behavior: No action.
    }

    // Default Hook: After Store
    protected function afterStore($model, Request $request)
    {
        // Default hook behavior: Log creation
       // Log::info("Created model: " . get_class($model), ['model' => $model]);
    }

    // Default Hook: Before Update
    protected function beforeUpdate($model, Request $request)
    {
        // Default hook behavior: No action.
    }

    // Default Hook: After Update
    protected function afterUpdate($model, Request $request)
    {
        // Default hook behavior: Log update
        //Log::info("Updated model: " . get_class($model), ['model' => $model]);
    }

    // Default Hook: Before Destroy
    protected function beforeDestroy($model)
    {
        // Default hook behavior: No action.
    }

    // Default Hook: After Destroy
    protected function afterDestroy($model)
    {
        // Default hook behavior: Log deletion
        //Log::info("Deleted model: " . get_class($model), ['model' => $model]);
    }
}
