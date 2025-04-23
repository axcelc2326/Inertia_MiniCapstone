<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Get search query from request
        $search = $request->input('search', '');

        // Fetch items based on search query, filtering name, description, and location
        $items = Item::where('name', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%")
                     ->orWhere('location', 'like', "%{$search}%")
                     ->orderByDesc('created_at')
                     ->paginate(9);

        // Return paginated items along with the search parameter
        return Inertia::render('Items/Index', [
            'items' => $items,
            'search' => $search,  // Pass search to keep the value in the search bar after page reload
        ]);
    }

    // Show create item form
    public function create()
    {
        return Inertia::render('Items/Create');
    }

    // Handle the creation of a new item
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:lost,found',
        ]);

        // Create a new item and save to the database
        $item = Item::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['name'] . '-' . Str::random(5)),
            'user_id' => auth()->id(),
        ]);

        // Redirect back to the index page with a success message
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }
}
