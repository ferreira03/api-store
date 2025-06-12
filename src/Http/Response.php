<?php

namespace App\Http;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface;

class Response
{
    private static function getHeaders(int $contentLength): array
    {
        return [
            'Content-Type' => ['application/json'],
            'Content-Length' => [(string) $contentLength],
            'Access-Control-Allow-Origin' => ['*'],
            'Access-Control-Allow-Methods' => ['GET, POST, PUT, PATCH, DELETE, OPTIONS'],
            'Access-Control-Allow-Headers' => ['DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization'],
            'Access-Control-Expose-Headers' => ['Content-Length,Content-Range'],
            'Access-Control-Max-Age' => ['1728000'],
        ];
    }

    public static function success(mixed $data = null, int $status = 200): ResponseInterface
    {
        $content = [
            'status' => 'success',
            'data' => $data,
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid(),
            ],
        ];

        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON response: ' . json_last_error_msg());
        }

        $response = new Psr7Response(
            $status,
            self::getHeaders(strlen($json)),
            $json
        );

        error_log('Success response: ' . $json);

        return $response;
    }

    public static function error(string $code, string $message, array $details = [], int $status = 400): ResponseInterface
    {
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

        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON response: ' . json_last_error_msg());
        }

        error_log('Error response: ' . $json);

        $response = new Psr7Response(
            $status,
            self::getHeaders(strlen($json)),
            $json
        );

        return $response;
    }
}
