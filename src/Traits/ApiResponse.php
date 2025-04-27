<?php

namespace Larapi\Zkrcrud\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse {

    // ✅ Success Response (for non-paginated)
    protected function successResponse($data, $statusCode = 200): JsonResponse {
        return response()->json([
            "data" => $data
        ], $statusCode);
    }

    // ✅ Paginated Response (Spatie style)
    protected function paginatedResponse($data, $statusCode = 200): JsonResponse {
        return response()->json([
            "data" => $data->items(),
            "meta" => [
                "current_page" => $data->currentPage(),
                "from" => $data->firstItem(),
                "last_page" => $data->lastPage(),
                "path" => request()->url(),
                "per_page" => $data->perPage(),
                "to" => $data->lastItem(),
                "total" => $data->total(),
            ],
            "links" => [
                "first" => $data->url(1),
                "last" => $data->url($data->lastPage()),
                "prev" => $data->previousPageUrl(),
                "next" => $data->nextPageUrl(),
            ]
        ], $statusCode);
    }

    // ✅ Error Response
    protected function errorResponse($message = "Something went wrong", $statusCode = 400, $errors = []): JsonResponse {
        return response()->json([
            "message" => $message,
            "errors" => $errors
        ], $statusCode);
    }
}
