<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * @param mixed|array $data
     * @param null $error
     * @param int $code
     * @param string $message
     * @param bool $success
     * @param array $trace
     * @return JsonResponse
     */
    public function response(
        $data = [],
        $error = null,
        int $code = 200,
        string $message = 'Успешно',
        bool $success = true,
        array $trace = []
    ): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'status' => $code,
            'error' => $error,
            'data' => $data,
            'trace' => $trace,
            'memory_get_usage' => memory_get_usage(),
        ], $code);
    }
}
