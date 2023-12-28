<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TheGuardianService
{
  public static function fetch(array $request) {

    $query_params = [
      'api-key' => config('services.the_guardian.key'),
      'show-fields' => 'thumbnail'
    ];

    if (count($request) > 0) {
      $query_params = [
        ...$query_params,
        ...$request,
      ];
    }

    $response = Http::get('https://content.guardianapis.com/search', $query_params);

    return $response->json();
  }
}
