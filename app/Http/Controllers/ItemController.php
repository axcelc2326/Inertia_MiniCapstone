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
        $search = $request->input('search', '');
        $status = $request->input('status', '');

        $items = Item::with('user') // ✅ eager load the user who reported the item
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(9)
            ->appends(['search' => $search, 'status' => $status]);

        $items->getCollection()->transform(function ($item) {
            $item->can_edit = auth()->user()->hasRole('admin') || $item->user_id === auth()->id();
            return $item;
        });

        return Inertia::render('Items/Index', [
            'items' => $items,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return Inertia::render('Items/Create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'required|string|max:255',
        'status' => 'required|in:lost,found',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
    ]);

    // Handle the image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('items', 'public');
    } else {
        $imagePath = null; // No image uploaded
    }

    // Create item
    Item::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'location' => $validated['location'],
        'status' => $validated['status'],
        'slug' => Str::slug($validated['name'] . '-' . Str::random(5)),
        'user_id' => auth()->id(),
        'image' => $imagePath, // Store image path in the database
    ]);

    return redirect()->route('items.index')->with('success', 'Item created successfully.');
}
    public function edit(Item $item)
    {
        if (!auth()->user()->hasRole('admin') && $item->user_id !== auth()->id()) {
            return redirect()->route('items.index')->with('error', 'Unauthorized action.');
        }

        return Inertia::render('Items/Edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, Item $item)
{
    if (!auth()->user()->hasRole('admin') && $item->user_id !== auth()->id()) {
        return redirect()->route('items.index')->with('error', 'Unauthorized action.');
    }

    // Validate the incoming data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'required|string',
        'status' => 'required|in:lost,found',
        'image' => 'nullable|image|max:2048',  // Allow image to be optional
    ]);


    // Continue with the update logic after validation
    $item->update([
        'name' => $request->name,
        'description' => $request->description,
        'location' => $request->location,
        'status' => $request->status,
    ]);

    // Handle image upload if there is an image
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $item->update(['image' => $path]);
    }

    return redirect()->route('items.index')->with('success', 'Item updated successfully');
}




    public function destroy(Item $item)
    {
        if (!auth()->user()->hasRole('admin') && $item->user_id !== auth()->id()) {
            return redirect()->route('items.index')->with('error', 'Unauthorized action.');
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
