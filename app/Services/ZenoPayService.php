<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZenoPayService
{
    private string $baseUrl = 'https://zenoapi.com/api/payments';

    /**
     * Get the API key from settings (always fetch fresh from database).
     */
    private function getApiKey(): string
    {
        return Setting::get('zenopay_api_key', '');
    }

    /**
     * Initiate a mobile money payment.
     *
     * @param  array<string, mixed>  $paymentData
     * @return array<string, mixed>
     */
    public function initiatePayment(array $paymentData): array
    {
        $apiKey = $this->getApiKey();

        // Check if API key is configured
        if (empty($apiKey)) {
            Log::error('ZenoPay API Key not configured');

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Payment gateway is not configured. Please contact administrator.',
            ];
        }

        $url = $this->baseUrl.'/mobile_money_tanzania';

        // Validate required fields
        $requiredFields = ['order_id', 'buyer_email', 'buyer_name', 'buyer_phone', 'amount'];
        foreach ($requiredFields as $field) {
            if (! isset($paymentData[$field])) {
                Log::error('ZenoPay Missing Required Field', ['field' => $field, 'payment_data' => $paymentData]);

                return [
                    'success' => false,
                    'status' => 'error',
                    'message' => "Missing required field: {$field}",
                ];
            }
        }

        // Ensure amount is a positive integer
        $amount = (int) $paymentData['amount'];
        if ($amount <= 0) {
            Log::error('ZenoPay Invalid Amount', ['amount' => $amount]);

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Invalid payment amount. Amount must be greater than 0.',
            ];
        }

        try {
            $requestPayload = [
                'order_id' => $paymentData['order_id'],
                'buyer_email' => $paymentData['buyer_email'],
                'buyer_name' => $paymentData['buyer_name'],
                'buyer_phone' => $paymentData['buyer_phone'],
                'amount' => $amount,
            ];

            Log::info('ZenoPay Payment Request', [
                'url' => $url,
                'payload' => $requestPayload,
                'api_key_length' => strlen($apiKey),
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $apiKey,
            ])->timeout(30)->post($url, $requestPayload);

            $statusCode = $response->status();
            $responseData = $response->json();

            // Log the full response for debugging
            Log::info('ZenoPay Payment Initiation Response', [
                'status_code' => $statusCode,
                'response' => $responseData,
                'payment_data' => $paymentData,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $responseData['status'] ?? 'unknown',
                    'resultcode' => $responseData['resultcode'] ?? null,
                    'message' => $responseData['message'] ?? 'Payment initiated successfully',
                    'order_id' => $responseData['order_id'] ?? $paymentData['order_id'],
                ];
            }

            // Log error response
            Log::error('ZenoPay Payment Initiation Failed', [
                'status_code' => $statusCode,
                'response' => $responseData,
                'payment_data' => $paymentData,
            ]);

            $errorMessage = $responseData['message'] ?? 'Payment initiation failed';
            if (isset($responseData['error'])) {
                $errorMessage = $responseData['error'];
            }

            return [
                'success' => false,
                'status' => 'error',
                'status_code' => $statusCode,
                'message' => $errorMessage,
                'response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('ZenoPay API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payment_data' => $paymentData,
            ]);

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to connect to payment gateway: '.$e->getMessage(),
                'exception' => get_class($e),
            ];
        }
    }

    /**
     * Check payment status by order ID.
     *
     * @return array<string, mixed>
     */
    public function checkOrderStatus(string $orderId): array
    {
        $url = $this->baseUrl.'/order-status';

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->getApiKey(),
            ])->get($url, [
                'order_id' => $orderId,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['resultcode']) && $data['resultcode'] === '000' && ! empty($data['data'])) {
                    $paymentData = $data['data'][0];

                    return [
                        'success' => true,
                        'status' => $paymentData['payment_status'] ?? 'PENDING',
                        'resultcode' => $data['resultcode'],
                        'message' => $data['message'] ?? 'Order status fetched successfully',
                        'transaction_id' => $paymentData['transid'] ?? null,
                        'channel' => $paymentData['channel'] ?? null,
                        'reference' => $paymentData['reference'] ?? null,
                        'amount' => $paymentData['amount'] ?? null,
                    ];
                }

                return [
                    'success' => false,
                    'status' => 'pending',
                    'message' => $data['message'] ?? 'Payment is still pending',
                ];
            }

            return [
                'success' => false,
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Failed to check payment status',
            ];
        } catch (\Exception $e) {
            Log::error('ZenoPay Status Check Error: '.$e->getMessage());

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to check payment status',
            ];
        }
    }

    /**
     * Check if API key is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->getApiKey());
    }
}
