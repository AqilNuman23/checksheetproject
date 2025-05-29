<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusRecord extends Model
{
    const DRAFT = 1;
    const ONHOLD = 2;
    const APPROVED = 3;
    const REJECTED = 4;
    const STATUS = [
        self::DRAFT => 'Draft',
        self::ONHOLD => 'On Hold',
        self::APPROVED => 'Approved',
        self::REJECTED => 'Rejected',
    ];

    protected $table = 'status_record';

    // make a connection with the checksheet table
    public function checksheets()
    {
        return $this->hasMany(Checksheet::class);
    }

    public function getStatusName()
    {
        return self::STATUS[$this->id] ?? 'Unknown';
    }
}
