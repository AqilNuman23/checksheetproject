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
        'document',
        'status_record_id',
        'submission_date',
        'warranty_expiry',
        'updated_by',
        'part_name',
        'part_no',
        'model',
        'material',
        'grade',
        'do_no',
        'colour',
        'lot_qty',
        'cavity',
        'gross_net_wt',
        'insp_date'
    ];

    protected $casts = [
        'submission_date' => 'date',
        'warranty_expiry' => 'date',
        'insp_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function statusRecord()
    {
        return $this->belongsTo(StatusRecord::class, 'status_record_id');
    }
    public function getStatusNameAttribute()
    {
        return $this->status_record_id 
            ? StatusRecord::STATUS[$this->status_record_id] 
            : 'N/A';
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