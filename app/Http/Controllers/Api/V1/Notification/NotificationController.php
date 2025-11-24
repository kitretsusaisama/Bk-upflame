<?php

namespace App\Http\Controllers\Api\V1\Notification;

use App\Http\Controllers\Controller;
use App\Domain\Notification\Models\Notification;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Domains\Notification\Services\NotificationService;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $notifications = Notification::paginate(15);
        
        return NotificationResource::collection($notifications);
    }

    /**
     * Send a new notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, NotificationService $notificationService): JsonResponse
    {
        $data = $request->validate([
            'type' => 'required|string',
            'channel' => 'required|string',
            'subject' => 'required|string',
            'body' => 'required|string',
            'recipient_id' => 'required|integer|exists:users,id',
            // Add other validation rules as needed
        ]);
        
        // Add tenant_id from the authenticated user
        $data['tenant_id'] = $request->user()->tenant_id;
        
        // Send the notification using the NotificationService
        $notification = $notificationService->send($data);
        
        return response()->json([
            'message' => 'Notification sent successfully',
            'notification_id' => $notification->id
        ], 201);
    }

    /**
     * Mark a notification as read.
     *
     * @param  \App\Domain\Notification\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $notification->update(['read_at' => now()]);
        
        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }
}