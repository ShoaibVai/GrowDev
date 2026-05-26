<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    /*
    |--------------------------------------------------------------------------
    | OpenRouter AI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OpenRouter API which provides access to multiple
    | AI models including GPT-4, Claude, Llama, Mistral, and 200+ others.
    | The API is called directly from the frontend for better performance.
    |
    | @see https://openrouter.ai/
    */
    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'model' => env('OPENROUTER_MODEL', 'openai/gpt-3.5-turbo'),
    ],

    /*
    |--------------------------------------------------------------------------
    | DEPRECATED: Google Gemini AI Configuration
    |--------------------------------------------------------------------------
    |
    | Gemini API has been replaced with OpenRouter.
    | These settings are kept for backwards compatibility only.
    | Please use 'openrouter' configuration above.
    |
    | @deprecated Use 'openrouter' configuration instead
    */
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'project' => env('GEMINI_PROJECT'),
        'project_name' => env('GEMINI_PROJECT_NAME'),
        'project_number' => env('GEMINI_PROJECT_NUMBER'),
        'model' => env('GEMINI_MODEL', 'gemini-flash-latest'),
    ],

];
