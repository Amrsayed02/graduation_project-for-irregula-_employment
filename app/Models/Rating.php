<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'vendor_id', 'rating', 'review'];

    // Relationship to the user who gave the rating
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to the vendor (user) being rated
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
