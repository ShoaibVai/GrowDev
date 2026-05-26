<?php

return [
    'ai' => [
        'timeout_seconds' => (int) env('TASK_AI_TIMEOUT_SECONDS', 90),
        'cache_ttl_hours' => (int) env('TASK_AI_CACHE_TTL_HOURS', 24),
    ],

    'timers' => [
        'default_estimate_hours' => (float) env('TASK_DEFAULT_ESTIMATE_HOURS', 4),
        'default_hours_per_day' => (float) env('TASK_DEFAULT_HOURS_PER_DAY', 8),
        'workday_start' => env('TASK_WORKDAY_START', '09:00'),
        'workday_end' => env('TASK_WORKDAY_END', '17:00'),
        'skip_weekends' => filter_var(env('TASK_SKIP_WEEKENDS', true), FILTER_VALIDATE_BOOL),
        'reminder_threshold_hours' => array_values(array_filter(array_map(
            static fn (string $hour) => (int) trim($hour),
            explode(',', env('TASK_REMINDER_THRESHOLD_HOURS', '24,4'))
        ))),
    ],

    'ci_gate_token' => env('GROWDEV_CI_GATE_TOKEN'),
];
