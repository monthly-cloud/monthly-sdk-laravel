<?php

return [
    'access_token' => env('MONTHLY_CLOUD_ACCESS_TOKEN', ''),
    'api_url' => env('MONTHLY_CLOUD_API_URL', 'https://api.monthly.cloud/api/'),
    'cache_ttl' => env('MONTHLY_CLOUD_CACHE_TTL', 60), // In seconds (Laravel 5.8+) or minutes (before Laravel 5.8).
];
