<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertResidencyDetail extends Model
{
    use HasFactory;

    protected $table = 'cert_residency_details';

    protected $fillable = [
        'document_request_id',
        'civil_status',
        'citizenship',
        'resident_years', 
        'purpose'         
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}