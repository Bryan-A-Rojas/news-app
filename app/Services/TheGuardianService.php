<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\NewsService;

class TheGuardianService extends NewsService
{
    private $http;

    public function __construct($url, $apiKey) {
        $this->http = Http::withUrlParameters([
            'endpoint' => config('services.the_guardian.url'),
        ])->withQueryParameters([
            'api-key' => config('services.the_guardian.key'),
        ]);
    }

    public function fetch($parameters) {
        $query_params = [
            'show-fields' => 'thumbnail'
        ];

        if (count($parameters) > 0) {
            $query_params = [
                ...$query_params,
                ...$parameters,
            ];
        }

        $response = $this->http
            ->withQueryParameters($query_params)
            ->get('{+endpoint}');

        return $response->json();
    }


    public function search($term) {
        $this->http = $this->http->withQueryParameters([
            'q' => $term,
        ]);

        return $this;
    }
}
