<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable; // <--- Kept as requested
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, Notifiable; 

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'start_date',
        'end_date',
        'status', 
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime', 
        'end_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
    public function getDisplayStatusAttribute()
    {
        $now = Carbon::now();
        $endDate = $this->end_date ? Carbon::parse($this->end_date) : null;

        if ($endDate && $endDate->isPast()) {
            return 'Ended';
        } elseif ($this->status === 'inactive') {
            return 'Inactive';
        } elseif ($this->start_date && Carbon::parse($this->start_date)->gt($now)) {
            return 'Upcoming';
        } else {
            return 'Ongoing';
        }
    }

    public function scopeOngoing($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                     ->where(function ($q) use ($now) {
                         $q->where('end_date', '>=', $now)
                           ->orWhereNull('end_date');
                     })
                     ->where('status', '!=', 'inactive');
    }

    public function scopeEnded($query)
    {
        $now = Carbon::now();
        return $query->whereNotNull('end_date')
                     ->where('end_date', '<', $now);
    }
}