<?php
$levels = \App\CtrAcademicProgram::selectRaw("distinct level, sort_by")->where('academic_type','BED')
        ->orderBy('sort_by')->get();
$strands =  \App\CtrAcademicProgram::selectRaw('distinct strand')->where('academic_code','SHS')->get();
?>
<?php
    if(Auth::user()->accesslevel == env('GUIDANCE_BED')){
    $layout = "layouts.appguidance_bed";
    $title = "Pre Sectioning";
    $title_level = "Incoming Level";
    $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
    } else {
    $layout = "layouts.appbedregistrar";
    $title = "Sectioning";
    $title_level = "Level";
    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first();
    }
    
    if($type == "pre_sectioning"){
        $title = "Pre Sectioning";
        $note = "Note: This is pre-sectioning. All changes in the section will take effect for the next school year and period.";
    }else{
        $title = "Sectioning";
        $note = "Note: This is sectioning and not pre-sectioning. All changes in the section will take effect to the current school year and period.";
    }
?>

@extends($layout)
@section('messagemenu')
 <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <!-- User Image -->
                       
                      </div>
                      <!-- Message title and timestamp -->
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <!-- The message -->
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
@endsection
@section('header')
<section class="content-header">
      <h1>
          
        {{$title}}
        <small>S.Y.: {{$school_year->school_year}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sectioning</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="col-md-12">
     <p>{{$note}}</p>
     <div class="col-md-6">
         <div class="box">
             <div class="box-body">
            <div class="col-md-6">
            <div class="form form-group">
             <label>{{$title_level}}</label>
             <select class="form form-control" id="level">
                 <option>Select Level</option>
                 @foreach($levels as $level)
                 <option>{{$level->level}}</option>
                 @endforeach
             </select>    
            </div>
            </div>
         <div class="col-md-6">
            <div class="form form-group strandDisplay">
             <label>Strand  </label>
             <select class="form form-control" id="strand">
                 <option>Select Strand</option>
                 @foreach($strands as $level)
                 <option>{{$level->strand}}</option>
                 @endforeach
             </select>    
            </div>
         </div>
         <div id="poplist">
         </div>
        </div>         
       </div>      
      </div>   
     <div class="col-md-6">
         <div class="box">
             <div class="box-body">
         <div class="col-md-6">
             <div class="form form-group">
                 <div id="section_control">
                 </div>    
             </div>    
         </div> <div class="clearfix"></div>   
         <div id="sectionDisplay">
             
         </div>    
     </div>    
         </div>
         </div>
 </div>    
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){
      $(".strandDisplay").fadeOut(300);  
      
      $("#level").on('change',function(e){
         if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
         $(".strandDisplay").fadeIn(300);
         $("#poplist").html("");
         $("#section_control").html("");
         } else {
         $(".strandDisplay").fadeOut(300);
         //poplist()
         popsection_control();
         }
      }); 
      
      $("#strand").on('change',function(e){
          //poplist();
          popsection_control();
      })
    });
    
    function poplist(){
        var array={};
       if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
         array['strand']=$("#strand").val();  
       }
       array['level']=$("#level").val();
        array['type']="{{$type}}";
       array['section']=$("#section").val();
       $.ajax({
           type:'GET',
           url:'/bedregistrar/ajax/studentlevel',
           data:array,
           success:function(data){
               $("#poplist").html(data);
           }
       });
    }
    
    function popsection_control(){
          var array={};
       
       array['level']=$("#level").val();
        array['type']="{{$type}}";
       if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
       array['strand']=$("#strand").val();  
       }
       $.ajax({
           type:'GET',
           url:'/bedregistrar/ajax/sectioncontrol',
           data:array,
           success:function(data){ 
               $("#section_control").html(data);
           
           }
       });
    }
    
    function popsectionlist(){
       poplist(); 
       var array={};
       array['section']=$("#section").val();
       array['level']=$("#level").val();
        array['type']="{{$type}}";
       if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
         array['strand']=$("#strand").val();  
       }
       $.ajax({
           type:'GET',
           url:'/bedregistrar/ajax/sectionlist',
           data:array,
           success:function(data){
               $("#sectionDisplay").html(data);
           }
       });
    }
    
    function change_section(idno){
        var array={};
        array['idno']=idno;
        array['level']=$("#level").val();
        array['section']=$("#section").val();
        array['type']="{{$type}}";
        $.ajax({
            type:'GET',
            url:'/bedregistrar/ajax/change_section',
            data:array,
            success:function(data){
                popsectionlist();
            }
        })
    }
</script>    
@endsection
