<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\SrsDocument;
use App\Services\AI\TaskGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateTaskOutlineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 180, 600];

    public function __construct(
        public int $projectId,
        public ?int $srsDocumentId,
        public string $runId,
        public int $requestedBy,
        public bool $mockAi = false,
    ) {
        $this->onQueue('ai');
    }

    public function handle(TaskGenerationService $service): void
    {
        $service->putRunStatus($this->runId, [
            'status' => 'outline_running',
            'layer' => 1,
        ]);

        $project = Project::findOrFail($this->projectId);
        $srsDocument = $this->srsDocumentId ? SrsDocument::find($this->srsDocumentId) : null;
        $outline = $service->generateOutline($project, $srsDocument, $this->runId, $this->mockAi);

        GenerateScaffoldPromptsJob::dispatch($this->projectId, $this->runId, $outline, $this->requestedBy, $this->mockAi)
            ->onQueue('ai');
    }

    public function failed(Throwable $exception): void
    {
        app(TaskGenerationService::class)->putRunStatus($this->runId, [
            'status' => 'failed',
            'layer' => 1,
            'error' => $exception->getMessage(),
        ]);
    }
}
