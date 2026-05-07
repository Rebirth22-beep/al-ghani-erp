<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Builds the standard API response envelope in one place.
 *
 * Controllers, middleware, and future exception handlers should use this class
 * instead of hand-building `['success' => ...]` arrays.
 */
class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = '',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        $response = ['success' => true];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($message !== '') {
            $response['message'] = $message;
        }

        if ($meta !== []) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }

    public static function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== []) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    public static function paginated(LengthAwarePaginator $paginator, ?string $resourceClass = null): JsonResponse
    {
        $items = $paginator->getCollection();
        $data = $resourceClass ? $resourceClass::collection($items) : $items;

        return self::success($data, meta: [
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'from'         => $paginator->firstItem(),
            'to'           => $paginator->lastItem(),
            'total'        => $paginator->total(),
        ]);
    }
}
