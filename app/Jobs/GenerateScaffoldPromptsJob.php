<?php

namespace App\Jobs;

use App\Models\Project;
use App\Services\AI\TaskGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateScaffoldPromptsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 180, 600];

    public function __construct(
        public int $projectId,
        public string $runId,
        public array $outline,
        public int $requestedBy,
        public bool $mockAi = false,
    ) {
        $this->onQueue('ai');
    }

    public function handle(TaskGenerationService $service): void
    {
        $service->putRunStatus($this->runId, [
            'status' => 'scaffolds_running',
            'layer' => 2,
        ]);

        $project = Project::findOrFail($this->projectId);
        $scaffolds = $service->generateScaffolds($project, $this->runId, $this->outline, $this->mockAi);

        GenerateTaskPromptsJob::dispatch($this->projectId, $this->runId, $this->outline, $scaffolds, $this->requestedBy, $this->mockAi)
            ->onQueue('ai');
    }

    public function failed(Throwable $exception): void
    {
        app(TaskGenerationService::class)->putRunStatus($this->runId, [
            'status' => 'failed',
            'layer' => 2,
            'error' => $exception->getMessage(),
        ]);
    }
}
