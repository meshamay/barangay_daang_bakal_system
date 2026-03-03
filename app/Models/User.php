<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string|null $resident_id
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $suffix
 * @property string|null $username
 * @property string|null $email
 * @property string|null $role
 * @property string|null $user_type
 * @property string|null $status
 * @property string|null $contact_number
 * @property string|null $address
 * @property string|null $photo_path
 * @property string|null $id_front_path
 * @property string|null $id_back_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'resident_id', 
        'last_name', 
        'first_name', 
        'middle_name', 
        'suffix',
        'photo_path', 
        'gender', 
        'age', 
        'civil_status', 
        'birthdate',
        'citizenship', 
        'place_of_birth', 
        'contact_number', 
        'email',
        'id_front_path', 
        'id_back_path', 
        'address', 
        'barangay',
        'city_municipality',
        'username', 
        'password',
        'plain_password',
        'user_type', 
        'role', 
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    public function documentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'resident_id');
    }

    public function complaints(): HasMany
   {
    return $this->hasMany(Complaint::class, 'user_id');
   }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->resident_id)) {
                $latestUserWithResidentId = self::where('resident_id', 'like', 'RS-%')
                    ->orderBy('resident_id', 'desc')
                    ->withTrashed() 
                    ->first();

                if ($latestUserWithResidentId) {
                    $latestNumber = (int) substr($latestUserWithResidentId->resident_id, 3);
                    $nextNumber = $latestNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                $user->resident_id = 'RS-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}