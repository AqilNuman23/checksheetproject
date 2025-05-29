<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class LogRecordChecksheet extends Model
   {
       protected $table = 'log_record_checksheet';

       protected $fillable = [
           'user_id',
           'checksheet_id',
           'status_record_id',
       ];

       // Define relationships
       public function user()
       {
           return $this->belongsTo(User::class);
       }

       public function product()
       {
           return $this->belongsTo(Product::class);
       }

       public function checksheet()
       {
           return $this->belongsTo(Checksheet::class);
       }

       public function statusRecord()
       {
           return $this->belongsTo(StatusRecord::class);
       }
   }