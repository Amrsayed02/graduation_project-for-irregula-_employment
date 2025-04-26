<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'vendor_id'];

    // Relationship to the user who favorited the vendor
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the vendor (user) being favorited
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
