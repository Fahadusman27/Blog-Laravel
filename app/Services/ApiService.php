<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.go_api.base_url');
    }

    /**
     * Create a pre-configured request with Bearer Token if exists
     */
    protected function client(): PendingRequest
    {
        $request = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout(5); // Fail fast jika backend down

        if (session()->has('api_token')) {
            $request->withToken(session('api_token'));
        }

        return $request;
    }

    public function getPosts()
    {
        return $this->client()->get('/posts')->throw()->json();
    }

    public function getCategories()
    {
        return $this->client()->get('/categories')->throw()->json();
    }

    public function login(array $credentials)
    {
        return $this->client()->post('/auth/login', $credentials)->throw()->json();
    }

    public function register(array $data)
    {
        return $this->client()->post('/auth/register', $data)->throw()->json();
    }

    /**
     * Generic GET request to Go backend
     */
    public function get(string $endpoint, array $query = [])
    {
        return $this->client()->get($endpoint, $query)->throw()->json();
    }

    /**
     * Generic POST request to Go backend
     */
    public function post(string $endpoint, array $data)
    {
        return $this->client()->post($endpoint, $data)->throw()->json();
    }

    /**
     * Generic PUT request to Go backend
     */
    public function put(string $endpoint, array $data)
    {
        return $this->client()->put($endpoint, $data)->throw()->json();
    }

    /**
     * Specific method to fetch user profile
     */
    public function getProfile()
    {
        return $this->get('/auth/profile');
    }
}
