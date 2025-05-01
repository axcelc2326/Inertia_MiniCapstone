<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $lostCount = Item::where('status', 'lost')->count();
            $foundCount = Item::where('status', 'found')->count();
            $returnedCount = Item::where('status', 'returned')->count();

            return Inertia::render('Dashboard', [
                'auth' => ['user' => $user],
                'role' => 'admin', // Pass the role
                'lostCount' => $lostCount,
                'foundCount' => $foundCount,
                'returnedCount' => $returnedCount,
            ]);
        } else {
            $lostCount = Item::where('status', 'lost')->where('user_id', $user->id)->count();
            $foundCount = Item::where('status', 'found')->where('user_id', $user->id)->count();
            $returnedCount = Item::where('status', 'returned')->where('user_id', $user->id)->count();

            return Inertia::render('Dashboard', [
                'auth' => ['user' => $user],
                'role' => 'user', // Pass the role
                'lostCount' => $lostCount,
                'foundCount' => $foundCount,
                'returnedCount' => $returnedCount,
            ]);
        }
    }
}

