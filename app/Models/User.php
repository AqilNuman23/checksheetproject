<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role', 'company_id', 'email_verified_at'];

    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function checksheets()
    {
        return $this->hasMany(Checksheet::class, 'supplier_id')->orWhere('qe_id', $this->id);
    }
}
