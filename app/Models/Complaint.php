<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_no',
        'incident_date',
        'incident_time',
        'defendant_name',
        'defendant_address',
        'level_urgency',
        'complaint_type',
        'complaint_statement',
        'status',
        'date_completed',
    ];

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}