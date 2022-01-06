<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeCollege extends Model
{
    
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $append  = ['full_name'];
    
    public function getFullNameAttribute(){
        $name = User::where('idno', $this->idno)->first();
        
        return $name->lastname.", ".$name->firstname." ".$name->middlename." ".$name->extensionname;
    }
     public function user(){
         return $this->hasOne(User::class,'idno','idno');
     }
}
