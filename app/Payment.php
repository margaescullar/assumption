<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    
    public function accounting()
    {
        return $this->hasMany(Accounting::class,'reference_id','reference_id');
    }
    
    public function checkReservation()
    {
        return $this->hasOne(Reservation::class,'reference_id','reference_id');
    }
}
