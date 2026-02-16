<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class BarangayOfficials extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_initial',
        'position',
        'photo_path',
        'created_by',
    ];

    use HasFactory;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

