<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $listings = Listing::query()
            ->when($request->input('transaction_type'), fn($query, $type) 
                => $query->where('transaction_type', $type))
            ->when($request->input('property_type'), fn($query, $type) 
                => $query->where('property_type', $type))
            ->with('user', 'images') // Load related images
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Listings/Index', [
            'listings' => $listings,
            'filters' => $request->only(['transaction_type', 'property_type'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Listings/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:rent,sale',
            'property_type' => 'required|string',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'area' => 'required|numeric|min:0',
            'location' => 'required|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validate each image
        ]);

        // Create the listing
        $listing = $request->user()->listings()->create($validated);

        // Handle multiple images
        foreach ($request->file('images') as $key => $image) {
            $path = $image->store('listings', 'public');
            
            $listing->images()->create([
                'path' => $path,
                'is_main' => $key === 0 // First image is the main image
            ]);
        }

        return redirect()->route('listings.index');
    }
}
