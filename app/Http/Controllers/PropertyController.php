<?php

namespace App\Http\Controllers;
use App\Models\Review;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lessor_id' => 'required',
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric',
            'availability' => 'required|boolean',
            'capacity' => 'required|integer',
        ]);

        // Set default values for properties not included in the request
        $propertyData = $request->all();
        $propertyData = array_merge([
            'availability' => true, // Default to true if not provided
            'capacity' => 0,        // Default to 0 if not provided
        ], $propertyData);

        Property::create($propertyData);
        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    public function show($id)
    {
        $property = Property::with('reviews')->find($id); // Load the property with reviews

        if (!$property) {
            abort(404, 'Property not found');
        }

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $request->validate([
            'lessor_id' => 'required',
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric',
            'availability' => 'required|boolean',
            'capacity' => 'required|integer',
        ]);

        // Set default values for properties not included in the request
        $propertyData = $request->all();
        $propertyData = array_merge([
            'availability' => $property->availability, // Preserve existing value if not updated
            'capacity' => $property->capacity,         // Preserve existing value if not updated
        ], $propertyData);

        $property->update($propertyData);
        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }
}