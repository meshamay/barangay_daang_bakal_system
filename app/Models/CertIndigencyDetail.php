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
        'resident_years'
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}