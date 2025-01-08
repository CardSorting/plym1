<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GoApiService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.goapi.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.goapi.key');
    }

    public function generateImages(string $prompt, string $aspectRatio = '1:1'): array
    {
        try {
            \Log::info('Initiating GoAPI request', [
                'prompt' => $prompt,
                'aspectRatio' => $aspectRatio,
                'apiKey' => $this->apiKey ? 'present' : 'missing'
            ]);

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/task', [
                'model' => 'midjourney',
                'task_type' => 'imagine',
                'input' => [
                    'prompt' => $prompt,
                    'aspect_ratio' => $aspectRatio,
                    'process_mode' => 'fast',
                    'skip_prompt_check' => true,
                    'bot_id' => 0,
                    'num_images' => 4,
                    'quality' => 'standard'
                ]
            ]);

            if ($response->failed()) {
                \Log::error('GoAPI request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to generate images: ' . $response->body());
            }

            $data = $response->json();
            \Log::info('GoAPI response received', [
                'status' => $response->status(),
                'response' => $data
            ]);

            // Check if we got a task ID first
            if (isset($data['data']['task_id'])) {
                $taskId = $data['data']['task_id'];
                \Log::info('Got task ID', ['task_id' => $taskId]);
                
                // Poll for task completion
                $maxAttempts = 30; // 30 seconds timeout
                $attempt = 0;
                
                while ($attempt < $maxAttempts) {
                    $statusResponse = Http::withHeaders([
                        'x-api-key' => $this->apiKey,
                    ])->get($this->baseUrl . '/task/' . $taskId);
                    
                    if ($statusResponse->failed()) {
                        throw new Exception('Failed to check task status: ' . $statusResponse->body());
                    }
                    
                    $statusData = $statusResponse->json();
                    \Log::info('Task status check', ['attempt' => $attempt, 'status' => $statusData]);
                    
                    if (isset($statusData['data']['status'])) {
                        $status = $statusData['data']['status'];
                        \Log::info('Task status check', ['status' => $status]);
                        
                        if ($status === 'completed') {
                            if (isset($statusData['data']['output']['image_urls']) && !empty($statusData['data']['output']['image_urls'])) {
                                return $statusData['data']['output']['image_urls'];
                            }
                            throw new Exception('No image URLs in completed task');
                        }
                        
                        if ($status === 'failed') {
                            $error = $statusData['data']['error']['message'] ?? 'Unknown error';
                            throw new Exception('Task failed: ' . $error);
                        }
                    }
                    
                    $attempt++;
                    usleep(500000); // Wait 500ms before next check
                }
                
                throw new Exception('Task timed out after ' . $maxAttempts . ' seconds');
            }
            
            throw new Exception('No task ID in response');

        } catch (Exception $e) {
            \Log::error('GoAPI service error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Error generating images: ' . $e->getMessage());
        }
    }
}
