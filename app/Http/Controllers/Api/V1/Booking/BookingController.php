<?php

namespace App\Http\Controllers\Api\V1\Booking;

use App\Http\Controllers\Controller;
use App\Domain\Booking\Models\Booking;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $bookings = Booking::paginate(15);
        
        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\BookingResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'scheduled_at' => 'required|date',
            'duration' => 'required|integer',
            // Add other validation rules as needed
        ]);
        
        // Add tenant_id from the authenticated user
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['tenant_id'] = $request->user()->tenant_id;
        
        $booking = Booking::create($data);
        
        return new BookingResource($booking);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return \App\Http\Resources\BookingResource
     */
    public function show(Booking $booking)
    {
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return \App\Http\Resources\BookingResource
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'scheduled_at' => 'sometimes|date',
            'duration' => 'sometimes|integer',
            'status' => 'sometimes|string',
            // Add other validation rules as needed
        ]);
        
        $booking->update($request->validated());
        
        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        
        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }
}