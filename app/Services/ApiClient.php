<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

class ApiClient
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SERVER_API_URL', 'http://127.0.0.1:8000'), '/');
    }

    protected function client()
    {
        return Http::baseUrl($this->baseUrl)->acceptJson();
    }

    public function get($path, $query = [])
    {
        try {
            $response = $this->client()->get(ltrim($path, '/'), $query);
        } catch (ConnectionException $e) {
            Log::error('ApiClient connection error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'exception' => $e->getMessage()]);
            throw new NotFoundHttpException('API unavailable: ' . $this->baseUrl, $e);
        }

        // If API returned server error, log and dump for debugging
        if ($response->serverError()) {
            Log::error('ApiClient server error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'status' => $response->status(), 'body' => $response->body()]);
            throw new \Exception('API server error');
        }

        // If resource not found, return null so controller can handle gracefully
        if ($response->status() === 404) {
            return null;
        }

        // return as stdClass / objects so views can use ->property
        return json_decode($response->body());
    }

    public function post($path, $data = [])
    {
        try {
            $response = $this->client()->post(ltrim($path, '/'), $data);
        } catch (ConnectionException $e) {
            Log::error('ApiClient connection error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'exception' => $e->getMessage()]);
            throw new NotFoundHttpException('API unavailable: ' . $this->baseUrl, $e);
        }

        if ($response->serverError()) {
            Log::error('ApiClient server error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'status' => $response->status(), 'body' => $response->body()]);
            throw new \Exception('API server error');
        }

        if ($response->status() === 404) {
            return null;
        }

        return json_decode($response->body());
    }

    public function put($path, $data = [])
    {
        try {
            $response = $this->client()->put(ltrim($path, '/'), $data);
        } catch (ConnectionException $e) {
            Log::error('ApiClient connection error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'exception' => $e->getMessage()]);
            throw new NotFoundHttpException('API unavailable: ' . $this->baseUrl, $e);
        }

        if ($response->serverError()) {
            Log::error('ApiClient server error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'status' => $response->status(), 'body' => $response->body()]);
            throw new \Exception('API server error');
        }

        if ($response->status() === 404) {
            return null;
        }

        return json_decode($response->body());
    }

    public function delete($path)
    {
        try {
            $response = $this->client()->delete(ltrim($path, '/'));
        } catch (ConnectionException $e) {
            Log::error('ApiClient connection error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'exception' => $e->getMessage()]);
            throw new NotFoundHttpException('API unavailable: ' . $this->baseUrl, $e);
        }

        if ($response->serverError()) {
            Log::error('ApiClient server error', ['url' => $this->baseUrl . '/' . ltrim($path, '/'), 'status' => $response->status(), 'body' => $response->body()]);
            throw new \Exception('API server error');
        }

        if ($response->status() === 404) {
            return null;
        }

        return json_decode($response->body());
    }
}
