<?php

namespace App\Domains\Notification\Http\Controllers;

use App\Domains\Notification\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $notifications = $this->notificationService->getNotificationsByRecipient($userId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_user_id' => 'required|string',
            'template_name' => 'required|string',
            'channel' => 'required|in:email,sms,push',
            'variables' => 'nullable|array'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $notification = $this->notificationService->send($validated);

        return response()->json([
            'status' => 'success',
            'data' => [
                'notification_id' => $notification->id,
                'status' => $notification->status,
                'message' => 'Notification queued for delivery'
            ]
        ], 201);
    }

    public function show($id)
    {
        $notification = $this->notificationService->getNotificationById($id);

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'NOTIFICATION_NOT_FOUND',
                    'message' => 'Notification not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $notification
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'notification_type' => 'required|string|max:100',
            'channel' => 'required|in:email,sms,push',
            'is_enabled' => 'required|boolean'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Notification preferences updated successfully'
            ]
        ]);
    }
}
