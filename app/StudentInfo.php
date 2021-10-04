<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    //
    
    public function studentInfoParentInfo(){
        return $this->hasOne(StudentInfoParentInfo::class,'idno','idno');
    }
}
