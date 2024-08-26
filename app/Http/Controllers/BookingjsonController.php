<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingjsonController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString(); // Get today's date
        $bookings = Booking::where('date', '>=', $today)->get(); // Fetch bookings from today onward

        // Group bookings by date and map to availability
        $dateAvailability = $bookings->groupBy('date')->map(function ($items) {
            // Initialize availability flags
            $hasFullDayBooking = false;
            $hasAfternoonBooking = false;
            $hasNightBooking = false;

            // Check for existing bookings by slot type
            foreach ($items as $item) {
                switch ($item->time_slot) {
                    case 'fullday':
                        $hasFullDayBooking = true;
                        break;
                    case 'afternoon':
                        $hasAfternoonBooking = true;
                        break;
                    case 'night':
                        $hasNightBooking = true;
                        break;
                }
            }

            // Determine if the date should be considered fully booked
            $isFullyBooked = $hasFullDayBooking || ($hasAfternoonBooking && $hasNightBooking);

            return [
                'isFullyBooked' => $isFullyBooked,
                'availableSlots' => [
                    'afternoon' => !$hasFullDayBooking && !$hasAfternoonBooking,
                    'night' => !$hasFullDayBooking && !$hasNightBooking,
                    'fullday' => !$hasFullDayBooking && !$isFullyBooked,
                ],
            ];
        });

        return response()->json([
            'dateAvailability' => $dateAvailability
        ]);
    }
}