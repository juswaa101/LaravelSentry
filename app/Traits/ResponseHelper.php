<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseHelper
{

    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function success($data = null, $message = null, $statusCode)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function error($message, $statusCode)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}
