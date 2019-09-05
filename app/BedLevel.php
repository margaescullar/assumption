<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class BedLevel extends Model
{
    //
    public function getFullNameAttribute(){
        $name = User::where('idno', $this->idno)->first();
        
        return $name->lastname.", ".$name->firstname." ".$name->middlename." ".$name->extensionname;
    }
}