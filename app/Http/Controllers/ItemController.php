<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Items/Index', [
            'items' => $items,
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:lost,found',
        ]);

        $item = Item::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['name'] . '-' . Str::random(5)),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }
}
