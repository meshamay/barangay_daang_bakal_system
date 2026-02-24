<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertIndigencyDetail extends Model
{
    use HasFactory;

    protected $table = 'cert_indigency_details';

    protected $fillable = [
        'document_request_id',
        'purpose',
        'resident_years',
        'certificate_of_being_indigent',
        'other_purpose',
        'indigency_category'
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}