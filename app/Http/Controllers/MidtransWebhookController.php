<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, MidtransService $midtransService)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received:', $payload);

        $handled = $midtransService->handleNotification($payload);

        if ($handled) {
            return response()->json(['status' => 'success', 'message' => 'Notification handled successfully']);
        }

        return response()->json(['status' => 'ignored', 'message' => 'Order not found or invalid payload'], 400);
    }
}
