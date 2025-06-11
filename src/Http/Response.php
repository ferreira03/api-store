<?php

namespace App\Http;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface;

class Response
{
    public static function success(mixed $data = null, int $status = 200): ResponseInterface
    {
        $response = new Psr7Response();

        $content = [
            'status' => 'success',
            'data' => $data,
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid(),
            ],
        ];

        $json = json_encode($content);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON response: ' . json_last_error_msg());
        }

        $response->getBody()->write($json);

        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }

    public static function error(string $code, string $message, array $details = [], int $status = 400): ResponseInterface
    {
        $response = new Psr7Response();

        $content = [
            'status' => 'error',
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid(),
            ],
        ];

        if (!empty($details)) {
            $content['error']['details'] = $details;
        }

        $json = json_encode($content);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON response: ' . json_last_error_msg());
        }

        $response->getBody()->write($json);

        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
