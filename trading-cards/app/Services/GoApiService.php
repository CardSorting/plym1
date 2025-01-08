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

    public function generateImage(string $prompt, string $aspectRatio = '1:1'): string
    {
        try {
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
                throw new Exception('Failed to generate image: ' . $response->body());
            }

            $data = $response->json();
            
            // In a real application, you would handle the task ID and poll for results
            // For now, we'll assume the image URL is returned directly
            return $data['image_url'] ?? throw new Exception('No image URL in response');

        } catch (Exception $e) {
            throw new Exception('Error generating image: ' . $e->getMessage());
        }
    }
}
