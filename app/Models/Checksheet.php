<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checksheet extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_id',
        'qe_id',
        'details',
        'document_path',
        'status_record_id',
        'submission_date',
        'warranty_expiry',
        'updated_by',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'warranty_expiry' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function status_record()
    {
        return $this->belongsTo(StatusRecord::class, 'status_record_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function qe()
    {
        return $this->belongsTo(User::class, 'qe_id');
    }
}