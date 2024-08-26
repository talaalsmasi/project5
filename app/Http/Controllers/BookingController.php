<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function showCheckInForm($propertyId)
    {
        $property = Property::findOrFail($propertyId); // Fetch the specific property

        // Fetch booked dates and time slots for the specified property
        $bookedDates = Booking::where('property_id', $propertyId)
            ->where('status', 'booked')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                $hasFullDay = $items->contains('time_slot', 'fullday');
                $hasAfternoon = $items->contains('time_slot', 'afternoon');
                $hasNight = $items->contains('time_slot', 'night');

                // Determine if the date is fully booked
                $isFullyBooked = $hasFullDay || ($hasAfternoon && $hasNight);

                return [
                    'isFullyBooked' => $isFullyBooked,
                    'availableSlots' => [
                        'afternoon' => !$isFullyBooked && !$hasAfternoon,
                        'night' => !$isFullyBooked && !$hasNight,
                        'fullday' => !$isFullyBooked,
                    ]
                ];
            })
            ->toArray();

        return view('checkin', compact('property', 'bookedDates', 'propertyId'));
    }

    public function storeBooking(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string|in:afternoon,night,fullday',
        ]);

        // Check if the date and time slot are available for the specified property
        $existingBookings = Booking::where('property_id', $request->property_id)
            ->where('date', $request->date)
            ->get();

        // Determine if the date should be treated as fully booked
        $hasFullDay = $existingBookings->contains('time_slot', 'fullday');
        $hasAfternoon = $existingBookings->contains('time_slot', 'afternoon');
        $hasNight = $existingBookings->contains('time_slot', 'night');
        $isFullyBooked = $hasFullDay || ($hasAfternoon && $hasNight);

        if ($isFullyBooked && $request->time_slot !== 'fullday') {
            return back()->with('error', 'Selected time slot is not available.');
        }

        // Create the booking
        Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $request->property_id,
            'date' => $request->date,
            'time_slot' => $request->time_slot,
            'status' => 'pending',
            'total_price' => 100, // Example static value, adjust as needed
        ]);

        return redirect()->route('properties', ['propertyId' => $request->property_id])->with('success', 'Booking successful!');
    }
}