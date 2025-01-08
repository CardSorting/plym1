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
                    'skip_prompt_check' => false,
                    'bot_id' => 0
                ],
                'config' => [
                    'service_mode' => '',
                    'webhook_config' => [
                        'endpoint' => '',
                        'secret' => ''
                    ]
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
                'hasImageUrls' => isset($data['output']['image_urls'])
            ]);
            
            // Extract all image URLs from the response
            $imageUrls = $data['output']['image_urls'] ?? [];
            if (empty($imageUrls)) {
                throw new Exception('No image URLs in response');
            }

            return $imageUrls;

        } catch (Exception $e) {
            \Log::error('GoAPI service error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Error generating images: ' . $e->getMessage());
        }
    }
}
