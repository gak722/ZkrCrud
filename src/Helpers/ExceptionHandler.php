<?php 
namespace Larapi\Zkrcrud\Helpers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use larapi\ZkrCrud\Traits\ApiResponse;
use Throwable;

trait  ExceptionHandler
{
use ApiResponse;
    protected function handleException(Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return $this->errorResponse('Unauthorized', 403);
        }
    
        if ($e instanceof ValidationException) {
            return $this->errorResponse(
                'Validation Failed',
                422,
                $e->validator->errors()
            );
        }
    
        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse('Resource not found', 404);
        }
    
        if ($e instanceof QueryException) {
            return $this->errorResponse('Database Error', 500, [
                'error' => $e->getMessage()
            ]);
        }
        return $this->errorResponse('Server Error', 500, [
            'error' => $e->getMessage()
        ]);
    }
}