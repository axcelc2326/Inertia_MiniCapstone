<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Item;

class ItemController extends Controller
{public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', ''); // get status from request

        $items = Item::query()
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

        // Add 'can_edit' flag
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
        ]);

        Item::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['name'] . '-' . Str::random(5)),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    // Show edit form for item
    public function edit(Item $item)
    {
        // Ensure user has permission to edit
        if (!auth()->user()->hasRole('admin') && $item->user_id !== auth()->id()) {
            return redirect()->route('items.index')->with('error', 'Unauthorized action.');
        }

        return Inertia::render('Items/Edit', [
            'item' => $item,
        ]);
    }

    // Update item in database
    public function update(Request $request, Item $item)
    {
        // Ensure user has permission to update
        if (!auth()->user()->hasRole('admin') && $item->user_id !== auth()->id()) {
            return redirect()->route('items.index')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:lost,found',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    // Delete item from database
    public function destroy(Item $item)
    {
        // Ensure user has permission to delete
        if (!auth()->user()->hasRole('admin') && $item->user_id !== auth()->id()) {
            return redirect()->route('items.index')->with('error', 'Unauthorized action.');
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
