<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Standard success envelope for every API response.
     *
     * Keeping this here avoids repeating the same array shape in every controller.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = '',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::success($data, $message, $status, $meta);
    }

    /**
     * Standard error envelope for expected API failures.
     */
    protected function errorResponse(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        return ApiResponse::error($message, $status, $errors);
    }

    /**
     * Standard paginated response.
     *
     * Pass a Resource class name when each row should be transformed, for example:
     *     return $this->paginatedResponse($customers, CustomerResource::class);
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, ?string $resourceClass = null): JsonResponse
    {
        return ApiResponse::paginated($paginator, $resourceClass);
    }
}
