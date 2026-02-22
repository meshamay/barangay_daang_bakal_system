<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use App\Models\CertClearanceDetail;
use App\Models\CertIndigencyDetail;
use App\Models\CertResidencyDetail;
use App\Models\CertCertificateDetail;
use App\Models\User;

class DocumentRequest extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'document_requests';

    protected $fillable = [
        'resident_id',      
        'document_type',    
        'purpose',          
        'date_requested',   
        'status',           
        'processed_by',     
        'tracking_number',
        'length_of_residency',
        'valid_id_number',   
        'registered_voter',
        'proof_file_path',
    ];

    /**
     * Relationship: A request belongs to ONE Resident (User).
     */
    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    /**
     * Relationship: A request is processed by ONE Admin (User).
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // =================================================================
    // NEW RELATIONSHIPS (Links to the specific data tables)
    // =================================================================

    /**
     * If type is 'Barangay Clearance', fetch details from cert_clearance_details
     */
    public function clearanceData()
    {
        return $this->hasOne(CertClearanceDetail::class, 'document_request_id');
    }

    /**
     * If type is 'Certificate of Indigency', fetch details from cert_indigency_details
     */
    public function indigencyData()
    {
        return $this->hasOne(CertIndigencyDetail::class, 'document_request_id');
    }

    /**
     * If type is 'Certificate of Residency', fetch details from cert_residency_details
     */
    public function residencyData()
    {
        return $this->hasOne(CertResidencyDetail::class, 'document_request_id');
    }

    /**
     * If type is 'Business Permit', fetch details from cert_business_details
     */
    public function certificateData()
{
    return $this->hasOne(CertCertificateDetail::class, 'document_request_id');
}
}