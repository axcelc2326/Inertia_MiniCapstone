<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'status',
        'user_id',
        'slug',
        'image', // Add image field
    ];

    // Define the relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
