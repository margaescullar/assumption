<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ClassLeadsController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_BE')) {
            $class_leads = \app\User::where('accesslevel', env('BED_CL'))->orderBy('lastname')->orderBy('firstname')->get();
            return view('reg_be.class_leads.viewlist', compact('class_leads'));
        }
    }
    
    function update_levels(Request $request){
        $delete_levels = \App\ClassLeadLevel::where('idno',$request->idno)->get();
        if(count($delete_levels)>0){
            foreach($delete_levels as $delete_level){
                $delete_level->delete();
            }
        }
        $note = "";
        if($request->pre_kinder == 'on'){
            $level = 'Pre-Kinder';
            $this->add_level($request,$level);
            $note=$note."".$level;
        }
        if($request->kinder == 'on'){
            $level = 'Kinder';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade1 == 'on'){
            $level = 'Grade 1';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade2 == 'on'){
            $level = 'Grade 2';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade3 == 'on'){
            $level = 'Grade 3';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade4 == 'on'){
            $level = 'Grade 4';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade5 == 'on'){
            $level = 'Grade 5';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade6 == 'on'){
            $level = 'Grade 6';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade7 == 'on'){
            $level = 'Grade 7';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade8 == 'on'){
            $level = 'Grade 8';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade9 == 'on'){
            $level = 'Grade 9';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade10 == 'on'){
            $level = 'Grade 10';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade11 == 'on'){
            $level = 'Grade 11';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        if($request->grade12 == 'on'){
            $level = 'Grade 12';
            $this->add_level($request,$level);
            $note=$note.",".$level;
        }
        
            \App\Http\Controllers\Admin\Logs::log("Update levels assigned to class leader $request->idno. $note.");
        
        return redirect('bedregistrar/class_leads');
    }
    
    function add_level($request,$level){
        $add_level = new \App\ClassLeadLevel();
        $add_level->idno = $request->idno;
        $add_level->level = $level;
        $add_level->save();
    }
}
