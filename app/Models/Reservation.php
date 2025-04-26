<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'time',
        'user_signup',
        'vendor_signup',
        'user_id',
        'vendor_id',
        'price',
        'description',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        // 'user_signup' => 'boolean',
        // 'vendor_signup' => 'boolean',
        'price' => 'decimal:2'
    ];


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function maintenanceWorker()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Mutators and Accessors
    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->format('Y-m-d');
    }

    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->time)->format('H:i');
    }
}
