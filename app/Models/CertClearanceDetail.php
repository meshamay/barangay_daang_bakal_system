<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertClearanceDetail extends Model
{
    use HasFactory;

    protected $table = 'cert_clearance_details';

    protected $fillable = [
        'document_request_id',
        'purpose',
        'cedula_no'
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}