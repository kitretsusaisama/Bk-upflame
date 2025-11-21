<?php

namespace App\Domains\Booking\Services;

use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Booking\Models\Booking;
use Illuminate\Support\Str;

class BookingService
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function createBooking($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        $data['booking_reference'] = 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        
        $data['status'] = 'processing';
        
        $booking = $this->bookingRepository->create($data);
        
        return $booking;
    }

    public function getBookingById($id)
    {
        $booking = $this->bookingRepository->findById($id);
        return $booking ? $booking->load(['user', 'provider', 'service', 'statusHistory']) : null;
    }

    public function getBookingsByTenant($tenantId, $limit = 20)
    {
        return $this->bookingRepository->findByTenant($tenantId, $limit);
    }

    public function getBookingsByUser($userId, $limit = 20)
    {
        return $this->bookingRepository->findByUser($userId, $limit);
    }

    public function confirmBooking($bookingId, $userId)
    {
        $booking = $this->bookingRepository->findById($bookingId);
        
        if (!$booking) {
            throw new \Exception('Booking not found');
        }
        
        $booking = $this->bookingRepository->update($bookingId, [
            'status' => 'confirmed'
        ]);
        
        $this->recordStatusChange($booking, 'confirmed', $userId, 'Booking confirmed');
        
        return $booking;
    }

    public function cancelBooking($bookingId, $userId)
    {
        $booking = $this->bookingRepository->findById($bookingId);
        
        if (!$booking) {
            throw new \Exception('Booking not found');
        }
        
        if (!in_array($booking->status, ['processing', 'confirmed'])) {
            throw new \Exception('Booking cannot be cancelled');
        }
        
        $booking = $this->bookingRepository->update($bookingId, [
            'status' => 'cancelled'
        ]);
        
        $this->recordStatusChange($booking, 'cancelled', $userId, 'Booking cancelled by user');
        
        return $booking;
    }

    public function completeBooking($bookingId, $userId)
    {
        $booking = $this->bookingRepository->findById($bookingId);
        
        if (!$booking) {
            throw new \Exception('Booking not found');
        }
        
        $booking = $this->bookingRepository->update($bookingId, [
            'status' => 'completed'
        ]);
        
        $this->recordStatusChange($booking, 'completed', $userId, 'Booking completed');
        
        return $booking;
    }

    protected function recordStatusChange(Booking $booking, $status, $changedBy, $notes)
    {
        $booking->statusHistory()->create([
            'id' => Str::uuid()->toString(),
            'status' => $status,
            'changed_by' => $changedBy,
            'notes' => $notes
        ]);
    }
}
