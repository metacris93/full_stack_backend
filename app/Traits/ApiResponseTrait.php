<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successApiResponse($data = null, $resourceCreated = false, string $message = '')
    {
        return response()
            ->json(
            [
                'success' => true,
                'message' => $message,
                'errors'  => [],
                'data'    => $data,
            ],
            $resourceCreated ? 201 : 200
        );
    }
    public function errorApiResponse(string $errorMessage, int $responseCode, array $errors = [])
    {
        return response()
            ->json(
            [
                'success' => false,
                'message' => $errorMessage,
                'errors'  => $errors,
                'data'    => null,
            ]
        );
    }
}
