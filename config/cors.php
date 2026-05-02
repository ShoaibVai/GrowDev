<?php

return [
    /*
     * CORS Configuration for GrowDev Backend API
     * 
     * Configure allowed origins for API requests from your Vercel frontend
     * and other consuming applications.
     * 
     * Set the environment variable CORS_ALLOWED_ORIGINS for production:
     * CORS_ALLOWED_ORIGINS=https://yourdomain.vercel.app,https://yourdomain.com
     */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://localhost:5173,http://localhost:8000')),

    'allowed_origins_patterns' => [
        // Match any Vercel deployment
        '#^https://.*\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
