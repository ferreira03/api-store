<?php

namespace App\Controllers\Store;

use App\Exceptions\StoreValidationException;
use App\Http\Response;
use App\Services\StoreService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateStoreController
{
    public function __construct(
        private readonly StoreService $storeService
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $params = []): ResponseInterface
    {
        error_log('CreateStoreController: Starting request processing');

        try {
            $body = $request->getBody()->getContents();
            error_log('CreateStoreController: Raw request body: ' . $body);

            if (empty($body)) {
                error_log('CreateStoreController: Empty request body');

                return Response::error(
                    'INVALID_REQUEST',
                    'Request body is required',
                    [],
                    400
                );
            }

            $data = json_decode($body, true);
            error_log('CreateStoreController: Decoded JSON data: ' . print_r($data, true));

            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log('CreateStoreController: Invalid JSON format: ' . json_last_error_msg());

                return Response::error(
                    'INVALID_JSON',
                    'Invalid JSON format. Please check the data structure.',
                    ['details' => json_last_error_msg()],
                    400
                );
            }

            if (!is_array($data)) {
                error_log('CreateStoreController: Data is not an array');

                return Response::error(
                    'INVALID_REQUEST',
                    'Request body must be a JSON object',
                    [],
                    400
                );
            }

            error_log('CreateStoreController: Received store data: ' . print_r($data, true));

            $store = $this->storeService->createStore(
                $data['name'] ?? '',
                $data['address'] ?? '',
                $data['city'] ?? '',
                $data['country'] ?? '',
                $data['postal_code'] ?? '',
                $data['phone'] ?? '',
                $data['email'] ?? '',
                $data['is_active'] ?? true
            );

            error_log('CreateStoreController: Store created successfully');
            error_log('CreateStoreController: Store data: ' . print_r($store->toArray(), true));

            $response = Response::success($store->toArray(), 201);
            error_log('CreateStoreController: Response created: ' . print_r($response, true));

            return $response;
        } catch (StoreValidationException $e) {
            error_log('CreateStoreController: Validation error: ' . $e->getMessage());
            error_log('CreateStoreController: Validation details: ' . print_r($e->getTechnicalMessage(), true));

            $response = Response::error(
                'VALIDATION_ERROR',
                $e->getMessage(),
                ['details' => $e->getTechnicalMessage()],
                400
            );

            error_log('CreateStoreController: Validation response: ' . $response->getBody()->getContents());

            return $response;
        } catch (Exception $e) {
            $errorDetails = [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];

            error_log('CreateStoreController: Error details: ' . print_r($errorDetails, true));

            return Response::error(
                'INTERNAL_ERROR',
                $e->getMessage(),
                [],
                500
            );
        }
    }
}
