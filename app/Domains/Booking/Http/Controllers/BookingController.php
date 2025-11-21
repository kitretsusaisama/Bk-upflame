<?php

namespace App\Domains\Booking\Http\Controllers;

use App\Domains\Booking\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $bookings = $this->bookingService->getBookingsByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'nullable|string',
            'service_id' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'nullable|integer',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'metadata' => 'nullable|array'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        $validated['user_id'] = $request->user()->id;
        
        $booking = $this->bookingService->createBooking($validated);

        return response()->json([
            'status' => 'success',
            'data' => [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'status' => $booking->status,
                'message' => 'Booking received. Confirmation will be sent.'
            ]
        ], 201);
    }

    public function show($id)
    {
        $booking = $this->bookingService->getBookingById($id);

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'BOOKING_NOT_FOUND',
                    'message' => 'Booking not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $booking
        ]);
    }

    public function status($id)
    {
        $booking = $this->bookingService->getBookingById($id);

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'BOOKING_NOT_FOUND',
                    'message' => 'Booking not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'status' => $booking->status,
                'scheduled_at' => $booking->scheduled_at
            ]
        ]);
    }

    public function confirm(Request $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $booking = $this->bookingService->confirmBooking($id, $userId);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'message' => 'Booking confirmed successfully'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'OPERATION_FAILED',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $booking = $this->bookingService->cancelBooking($id, $userId);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'message' => 'Booking has been cancelled successfully'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'OPERATION_FAILED',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    public function complete(Request $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $booking = $this->bookingService->completeBooking($id, $userId);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'message' => 'Booking marked as completed'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'OPERATION_FAILED',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }
}
