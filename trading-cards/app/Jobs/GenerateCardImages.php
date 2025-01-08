<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\GoApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateCardImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Task $task
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GoApiService $goApiService): void
    {
        try {
            Log::info('Starting image generation job', ['task_id' => $this->task->id]);
            
            $this->task->markAsProcessing();

            $prompt = $this->task->input['prompt'];
            $imageUrls = $goApiService->generateImages($prompt);

            $this->task->markAsCompleted(['image_urls' => $imageUrls]);
            
            Log::info('Image generation completed', [
                'task_id' => $this->task->id,
                'image_count' => count($imageUrls)
            ]);
        } catch (\Exception $e) {
            Log::error('Image generation failed', [
                'task_id' => $this->task->id,
                'error' => $e->getMessage()
            ]);
            
            $this->task->markAsFailed($e->getMessage());
            
            throw $e;
        }
    }
}
