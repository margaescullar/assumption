<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});
*/

include_once 'web2.php';
include_once 'web3.php';
include_once 'TOR.php';


Route::get('/password/reset/{token}/{idno}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::post('/set_password','HomeController@set_password');

//Route::get('update_computerized_id','Updater@updateComputerizedID');
//Route::get('remove_computerized_id','Updater@removeDM');
//Route::get('update_college_ledger','Updater@updateCollegeLedger');

//REG ASSESSMENT
Route::get('/registrar_college/assessment2/{idno}/{school_year}/{period}', 'RegistrarCollege\Assessment\AssessmentController@index2');
Route::get('/registrar_college/assessment/readvise/{idno}', 'RegistrarCollege\Assessment\AssessmentController@readvise');
Route::post('/registrar_college/assessment/set_up_school_year', 'RegistrarCollege\Assessment\AssessmentController@set_up_year');

//Registrar College - MAIN//////////////////////////////////////////////////////
Route::get('/ajax/registrar_college/getstudentlist', 'RegistrarCollege\Ajax\GetStudentList_ajax@getstudentlist');

//Registrar College - View Info/////////////////////////////////////////////////
Route::get('registrar_college/view_info/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@view_info');
Route::get('registrar_college/print_info/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@print_info');
Route::post('registrar_college/save_info/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@save_info');
//Registrar College - Student Record////////////////////////////////////////////
Route::get('registrar_college/student_record/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@view_record');
Route::get('registrar_college/view_transcript/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@view_transcript');
Route::get('registrar_college/print_curriculum_record/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@print_curriculum_record');
Route::get('registrar_college/edit/college_grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@edit_college_grades2018');
Route::post('registrar_college/edit_now/college_grades','RegistrarCollege\StudentRecord\StudentRecordController@edit_now_college_grades2018');
Route::get('registrar_college/edit/grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@edit_college_grades');
Route::post('registrar_college/edit_now/grades','RegistrarCollege\StudentRecord\StudentRecordController@edit_now_college_grades');
Route::get('registrar_college/edit/credit_grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@edit_credit_grades');
Route::post('registrar_college/edit_now/credit_grades','RegistrarCollege\StudentRecord\StudentRecordController@edit_now_credit_grades');
Route::get('registrar_college/add_record/{id}','RegistrarCollege\StudentRecord\StudentRecordController@add_record');
Route::post('registrar_college/add_record_now','RegistrarCollege\StudentRecord\StudentRecordController@add_record_now');
Route::get('registrar_college/credit_course/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@credit_course');
Route::post('registrar_college/add_now/credit_course','RegistrarCollege\StudentRecord\StudentRecordController@add_now_credit_course');

//DELETE TRANSCRIPT RECORD
Route::get('registrar_college/delete_now/college_grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@delete_now_college_grades2018');
Route::get('registrar_college/delete_now/grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@delete_now_college_grades');
Route::get('registrar_college/delete_now/credit_grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@delete_now_credit_grades');

//SET AS CREDIT TRANSCRIPT RECORD
Route::get('registrar_college/set_as_credit_now/college_grades/{id}','RegistrarCollege\StudentRecord\StudentRecordController@set_as_credit_now_college_grades2018');
Route::get('registrar_college/print_credited_courses/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@print_credited_courses');

//Registrar College Advising
//Set Up
Route::get('/registrar_college/advising/set_up', 'RegistrarCollege\Advising\AdvisingController@index');
Route::post('/registrar_college/advising/save_set_up', 'RegistrarCollege\Advising\AdvisingController@save');
//Advising Statistics
Route::get('/registrar_college/advising/advising_statistics', 'RegistrarCollege\Advising\AdvisingStatistics@index');
Route::get('/registrar_college/advising/sectioning/{course_code}', 'RegistrarCollege\Advising\AdvisingStatistics@sectioning');
//Ajax Advising Statistics
Route::get('/ajax/registrar_college/advising/getstudentlist', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@getstudentlist');
Route::get('/ajax/registrar_college/advising/get_advising_statistics', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@get_advising_statistics');
Route::get('/ajax/registrar_college/advising/get_advising_statistics', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@get_advising_statistics');
Route::get('/ajax/registrar_college/advising/get_advising_statistics', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@get_advising_statistics');
Route::get('/ajax/registrar_college/advising/get_advising_statistics', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@get_advising_statistics');
Route::get('/ajax/registrar_college/advising/get_advising_statistics', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@get_advising_statistics');
Route::get('/ajax/registrar_college/advising/get_advising_statistics', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@get_advising_statistics');
Route::get('/ajax/registrar_college/advising/addtosection', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@addtosection');
Route::get('/ajax/registrar_college/advising/getsection', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@getsection');
Route::get('/ajax/registrar_college/advising/getschedulestudentlist', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@getschedulestudentlist');
Route::get('/ajax/registrar_college/advising/removetosection', 'RegistrarCollege\Advising\Ajax\AjaxAdvisingStatistics@removetosection');
//Assigning of Schedules
Route::get('/registrar_college/advising/assigning_of_schedules/{idno}', 'RegistrarCollege\Advising\AssigningOfSchedules@index');
Route::post('/registrar_college/advising/assign_schedule', 'RegistrarCollege\Advising\AssigningOfSchedules@assign_schedule');
//Ajax Assigning of Schedule individually
Route::get('/ajax/registrar_college/advising/get_section', 'RegistrarCollege\Advising\Ajax\AssignSchedule_ajax@get_section');

//Adding/Dropping///////////////////////////////////////////////////////////////
Route::get('/registrar_college/adding_dropping/{idno}','RegistrarCollege\AddingDropping\AddingDroppingController@index');
Route::get('/registrar_college/remove_adding_dropping/{idno}/{id}','RegistrarCollege\AddingDropping\AddingDroppingController@remove');
Route::get('/registrar_college/process_adding_dropping/{status}/{idno}','RegistrarCollege\AddingDropping\AddingDroppingController@process');
//Ajax
Route::get('/ajax/registrar_college/adding_dropping/search_offer/','RegistrarCollege\AddingDropping\Ajax\AddingDropping_ajax@index');
Route::get('/ajax/registrar_college/adding_dropping/adding/','RegistrarCollege\AddingDropping\Ajax\AddingDropping_ajax@adding');
Route::get('/ajax/registrar_college/adding_dropping/dropping/','RegistrarCollege\AddingDropping\Ajax\AddingDropping_ajax@dropping');
Route::get('/ajax/registrar_college/adding_dropping/show/','RegistrarCollege\AddingDropping\Ajax\AddingDropping_ajax@show');

//Registrar College Curriculum Management///////////////////////////////////////
//Free Section - College
Route::get('/registrar_college/curriculum_management/view_offering_free_section','RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@viewofferings_free_section');
Route::get('/ajax/registrar_college/curriculum_management/display_others', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@display_others');
//Tutorial Classes - College
Route::get('/registrar_college/curriculum_management/view_tutorials','RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@viewofferings_tutorials');
Route::get('/ajax/registrar_college/curriculum_management/display_others_tutorials', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@display_others_tutorials');
//Curriculum
Route::get('/registrar_college/curriculum_management/curriculum', 'RegistrarCollege\CurriculumManagement\CurriculumController@index');
Route::get('/registrar_college/curriculum_management/view_curricula/{program_code}', 'RegistrarCollege\CurriculumManagement\CurriculumController@viewcurricula');
Route::get('/registrar_college/curriculum_management/list_curriculum/{program_code}/{curriculum_year}','RegistrarCollege\CurriculumManagement\CurriculumController@listcurriculum');
//Add Electives
Route::get('/registrar_college/curriculum_management/add_electives','RegistrarCollege\CurriculumManagement\AddElectivesController@index');
//Ajax Electives
Route::get('/ajax/registrar_college/curriculum_management/get_electives', 'RegistrarCollege\CurriculumManagement\Ajax\electives_ajax@getelectives');
Route::get('/ajax/registrar_college/curriculum_management/add_electives', 'RegistrarCollege\CurriculumManagement\Ajax\electives_ajax@addelectives');
Route::get('/ajax/registrar_college/curriculum_management/remove_electives', 'RegistrarCollege\CurriculumManagement\Ajax\electives_ajax@removeelectives');
//Upload Curriculum
Route::get('/registrar_college/curriculum_management/upload_curriculum', 'RegistrarCollege\CurriculumManagement\UploadCurriculumController@index');
Route::post('/registrar_college/curriculum_management/upload', 'RegistrarCollege\CurriculumManagement\UploadCurriculumController@upload');
//View Course Offering
Route::get('/registrar_college/curriculum_management/view_course_offering', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@index');
//Ajax View Course Offering
Route::get('/ajax/registrar_college/curriculum_management/get_sections', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_sections');
Route::get('/ajax/registrar_college/curriculum_management/get_offerings', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_offerings');
//Course Offering
Route::get('/registrar_college/curriculum_management/course_offering', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@index');
Route::get('/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\CourseOfferingController@viewofferings');
//Ajax Course Offering
Route::get('/ajax/registrar_college/curriculum_management/view_offering/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcurriculum');
Route::get('/ajax/registrar_college/curriculum_management/view_offering_fs/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcurriculum_fs');
Route::get('/ajax/registrar_college/curriculum_management/view_offering_tutorials/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcurriculum_tutorials');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offered/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcourse_offered');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offered_fs/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcourse_offered_fs');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offered_tutorials/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@listcourse_offered_tutorials');
Route::get('/ajax/registrar_college/curriculum_management/add_to_course_offered/{course_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_to_course_offered');
Route::get('/ajax/registrar_college/curriculum_management/add_to_course_offered_fs/{course_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_to_course_offered_fs');
Route::get('/ajax/registrar_college/curriculum_management/add_to_course_offered_tutorials/{course_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_to_course_offered_tutorials');
Route::get('/ajax/registrar_college/curriculum_management/add_all_to_course_offered/', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@add_all_courses');
Route::get('/ajax/registrar_college/curriculum_management/remove_course_offered/{id}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@remove_course');
Route::get('/ajax/registrar_college/curriculum_management/remove_course_offered_fs/{id}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@remove_course_fs');
Route::get('/ajax/registrar_college/curriculum_management/remove_course_offered_tutorials/{id}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@remove_course_tutorials');
Route::get('/ajax/registrar_college/curriculum_management/add_offering_electives', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@addelectives');
Route::get('/ajax/registrar_college/curriculum_management/add_offering_electives_fs', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@addelectives_fs');
Route::get('/ajax/registrar_college/curriculum_management/add_offering_electives_tutorials', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@addelectives_tutorials');
Route::get('/ajax/registrar_college/curriculum_management/get_sectionname', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@getsectionname');
Route::get('/ajax/registrar_college/curriculum_management/update_section_name/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\courseoffering_ajax@update_section_name');
//Course Schedule
Route::get('/registrar_college/curriculum_management/course_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@index');
Route::get('/registrar_college/curriculum_management/edit_course_schedule/{course_offering_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@edit_course_schedule');
Route::post('/registrar_college/curriculum_management/add_course_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@add_course_schedule');
Route::post('/registrar_college/curriculum_management/edit_room_schedule', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@edit_room_schedule');
Route::get('/registrar_college/curriculum_management/merge_schedule/{schedule_id}/{course_id}','RegistrarCollege\CurriculumManagement\CourseScheduleController@merge_schedule');
Route::get('/registrar_college/curriculum_management/unmerged_schedule/{course_offering_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@unmerged_schedule');
Route::get('/registrar_college/curriculum_management/add_tba/{course_offering_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@add_tba');
Route::get('/registrar_college/curriculum_management/delete_schedule/{schedule_id}/{course_id}', 'RegistrarCollege\CurriculumManagement\CourseScheduleController@remove_schedule');
//Ajax Course Schedule
Route::get('/ajax/registrar_college/curriculum_management/course_to_schedule/{program_code}', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@listcourse_to_schedule');
Route::get('/ajax/registrar_college/curriculum_management/show_available_rooms/', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@show_available_rooms');
Route::get('/ajax/registrar_college/curriculum_management/get_section/', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@get_section');
Route::get('/ajax/registrar_college/curriculum_management/edit_schedule', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@edit_room_schedule');
Route::get('/ajax/registrar_college/curriculum_management/show_available_rooms2/', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@show_available_rooms2');
Route::get('/ajax/registrar_college/curriculum_management/edit_schedule', 'RegistrarCollege\CurriculumManagement\Ajax\coursescheduling_ajax@edit_room_schedule');
//Assign Instructor
Route::get('/registrar_college/curriculum_management/faculty_loading', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@index');
Route::get('/registrar_college/curriculum_management/edit_faculty_loading/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@edit_faculty_loading');
Route::post('/registrar_college/curriculum_management/add_faculty_loading', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@add_faculty_loading');
Route::get('/registrar_college/curriculum_management/remove_faculty_loading/{id}/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@remove_faculty_loading');
Route::get('/registrar_college/curriculum_management/print_faculty_loading/{idno}', 'RegistrarCollege\CurriculumManagement\FacultyLoadingController@print_faculty_loading');
//Ajax Assign Instructor
Route::get('/ajax/registrar_college/curriculum_management/show_available_loads/','RegistrarCollege\CurriculumManagement\Ajax\facultyloading_ajax@show_available_loads');
//Edit Schedule
Route::get('/registrar_college/curriculum_management/edit_schedule', 'RegistrarCollege\CurriculumManagement\ScheduleEditorController@index');
//Room Schedule Plotting
Route::get('/registrar_college/curriculum_management/view_room_schedule', 'RegistrarCollege\CurriculumManagement\ViewRoomSchedule@index');
Route::get('/ajax/registrar_college/curriculum_management/get_rooms', 'RegistrarCollege\CurriculumManagement\Ajax\AjaxViewRoomSchedule@view_rooms');
Route::get('/ajax/registrar_college/curriculum_management/generateRoom', 'RegistrarCollege\CurriculumManagement\Ajax\AjaxViewRoomSchedule@generateRoom');
Route::get('/registrar_college/curriculum_management/print_room_schedule/{school_year}/{period}/{room}', 'RegistrarCollege\CurriculumManagement\ViewRoomSchedule@print_room_schedule');
//Update list of rooms
Route::get('/registrar_college/curriculum_management/update_rooms', 'RegistrarCollege\CurriculumManagement\UpdateRooms@index');
Route::get('/registrar_college/curriculum_management/delete_room/{id}', 'RegistrarCollege\CurriculumManagement\UpdateRooms@delete_room');
Route::post('/registrar_college/curriculum_management/add_room', 'RegistrarCollege\CurriculumManagement\UpdateRooms@add_room');

//Update section limit
Route::get('/registrar_college/curriculum_management/section_limit', 'RegistrarCollege\CurriculumManagement\UpdateRooms@section_limit');
Route::post('/registrar_college/curriculum_management/update_section_limit', 'RegistrarCollege\CurriculumManagement\UpdateRooms@update_section_limit');
//Registrar College Admission///////////////////////////////////////////////////
//New Student
Route::get('/registrar_college/admission/new_student','RegistrarCollege\Admission\NewStudentController@index');
Route::post('/registrar_college/admission/add_new_student','RegistrarCollege\Admission\NewStudentController@add_new_student');

//Registrar College Instructors
//View Instructors
Route::get('/registrar_college/instructor/view_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@index');
Route::get('/registrar_college/instructor/add_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@view_add');
Route::post('/registrar_college/instructor/add_new_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@add');
Route::get('/registrar_college/instructor/modify_instructor/{idno}', 'RegistrarCollege\Instructor\ViewInstructorsController@view_modify');
Route::get('/registrar_college/instructor/enable_disable/{idno}', 'RegistrarCollege\Instructor\ViewInstructorsController@enable_disable');
Route::post('/registrar_college/instructor/modify_old_instructor', 'RegistrarCollege\Instructor\ViewInstructorsController@modify');


//Registrar Grade Management
Route::get('/registrar_college/print_grade_list/{school_year}/{period}/{schedule_id}/{course_code}', 'CollegeInstructor\Grades\GradesController@print_grade');
//Change Grading Period
Route::get('/registrar_college/grade_management/grading_sy', 'RegistrarCollege\GradeManagement\GradingSY@read');
Route::post('/registrar_college/grade_management/grading_sy/{id}', 'RegistrarCollege\GradeManagement\GradingSY@update');
//Close/Open Grade Module
Route::get('/registrar_college/grade_management/open_close', 'RegistrarCollege\GradeManagement\OpenCloseController@setup');
Route::post('/registrar_college/grade_management/open_close/submit', 'RegistrarCollege\GradeManagement\OpenCloseController@submit');
Route::get('/ajax/registrar_college/grade_management/update_open_close/midterm', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@update_midterm');
Route::get('/ajax/registrar_college/grade_management/update_open_close/finals', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@update_finals');
//View Grades
Route::get('/registrar_college/grade_management/view_grades/{school_year}/{period}', 'RegistrarCollege\GradeManagement\GradesController@view_grades');
//Ajax View Grades
Route::get('/ajax/registrar_college/grade_management/get_schedules', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@view_grades');
Route::get('/ajax/registrar_college/grade_management/get_list_students', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@get_list_students');
Route::get('/ajax/registrar_college/grade_management/get_oldlist_students', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@get_oldlist_students');
Route::get('/ajax/registrar_college/grade_management/lock/{idno}/{school_year}/{period}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@lock');
Route::get('/ajax/registrar_college/grade_management/unlock/{idno}/{school_year}/{period}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@unlock');
Route::get('/ajax/registrar_college/grade_management/approve_all_midterm/{school_year}/{period}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@approve_all_midterm');
Route::get('/ajax/registrar_college/grade_management/cancel_all_midterm/{school_year}/{period}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@cancel_all_midterm');
Route::get('/ajax/registrar_college/grade_management/approve_all_finals/{school_year}/{period}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@approve_all_finals');
Route::get('/ajax/registrar_college/grade_management/cancel_all_finals/{school_year}/{period}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@cancel_all_finals');
Route::get('/ajax/registrar_college/grades/change_midterm/{idno}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@change_midterm');
Route::get('/ajax/registrar_college/grades/change_finals/{idno}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@change_finals');
Route::get('/ajax/registrar_college/grades/change_completion/{idno}', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@change_completion');
//Report Cards
Route::get('/registrar_college/grade_management/report_card', 'RegistrarCollege\GradeManagement\GradesController@report_card');
Route::get('/ajax/registrar_college/grade_management/generate_card', 'RegistrarCollege\GradeManagement\Ajax\AjaxViewGrades@generate_card');
Route::post('/registrar_college/grade_management/print_card_pdf','RegistrarCollege\GradeManagement\GradesController@print_card_pdf');
Route::get('/registrar_college/grade_management/print_report_card/{school_year}/{period}/{idno}', 'RegistrarCollege\GradeManagement\GradesController@print_report_card_individually');

//Statistics of Grades
Route::get('/registrar_college/grade_management/statistics_of_grades/{school_year}/{period}', 'RegistrarCollege\GradeManagement\GradesController@statistics_of_grades');
Route::get('/registrar_college/print_statistics_of_grades/{school_year}/{period}', 'RegistrarCollege\GradeManagement\GradesController@print_statistics_of_grade');

//Incomplete Grades
Route::get('/registrar_college/grade_management/{type}/{school_year}/{period}/{term}', 'RegistrarCollege\GradeManagement\GradesController@incomplete_grades');
Route::get('/registrar_college/print_grade_incomplete/{type}/{school_year}/{period}/{term}', 'RegistrarCollege\GradeManagement\GradesController@print_incomplete_grade');

//Registrar College Reports/////////////////////////////////////////////////////
//Student List
Route::get('/registrar_college/reports/student_list/search','RegistrarCollege\Reports\StudentListController@search');
Route::get('/registrar_college/reports/student_list/print_search/{school_year}/{level}/{period}/{program_code}','RegistrarCollege\Reports\StudentListController@print_search');
Route::get('/registrar_college/reports/student_list/per_course','RegistrarCollege\Reports\StudentListController@per_course');
Route::get('/registrar_college/reports/student_list/print_per_course/{course}/{schedule_id}/{school_year}/{level}/{period}/{program_code}','RegistrarCollege\Reports\StudentListController@print_per_course');
Route::get('/registrar_college/reports/student_list/per_instructor','RegistrarCollege\Reports\StudentListController@per_instructor');
Route::get('/registrar_college/reports/student_list/print_per_instructor/{instructor_id}/{school_year}/{period}/{schedule_id}/{course_code}','RegistrarCollege\Reports\StudentListController@print_per_instructor');
//Ajax Student List
Route::get('/ajax/registrar_college/reports/student_list/search','RegistrarCollege\Reports\Ajax\StudentList_ajax@search');
Route::get('/ajax/registrar_college/reports/student_list/select_section','RegistrarCollege\Reports\Ajax\StudentList_ajax@select_section');
Route::get('/ajax/registrar_college/reports/student_list/select_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@select_course');
Route::get('/ajax/registrar_college/reports/student_list/list_per_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@list_per_course');
Route::get('/ajax/registrar_college/reports/student_list/list_per_instructor/get_course','RegistrarCollege\Reports\Ajax\StudentList_ajax@get_course');
Route::get('/ajax/registrar_college/reports/student_list/list_per_instructor/get_schedule','RegistrarCollege\Reports\Ajax\StudentList_ajax@get_schedule');
Route::get('/ajax/registrar_college/reports/student_list/list_per_instructor/getstudentlist','RegistrarCollege\Reports\Ajax\StudentList_ajax@getstudentlist');
//Enrollment Statistics
Route::get('/registrar_college/reports/enrollment_statistics/{school_year}/{period}','RegistrarCollege\Reports\EnrollmentStatisticsController@index');
Route::get('/registrar_college/reports/enrollment_statistics/print_enrollment_statistics/{school_year}/{period}','RegistrarCollege\Reports\EnrollmentStatisticsController@print_statistics');
Route::get('/registrar_college/reports/enrollment_statistics/print_enrollment_official/{school_year}/{period}','RegistrarCollege\Reports\EnrollmentStatisticsController@print_official');
Route::get('registrar_college/reports/update_enrollment_statistics/{program_code}/{school_year}/{period}','RegistrarCollege\Reports\EnrollmentStatisticsController@update_display');


//Dean - MAIN///////////////////////////////////////////////////////////////////
Route::get('/ajax/dean/getstudentlist','Dean\Ajax\GetStudentList_ajax@getstudentlist');
//DEAN - Student Record////////////////////////////////////////////
Route::get('college/student_record/{idno}','Dean\StudentRecord\StudentRecordController@view_record');
Route::get('college/view_transcript/{idno}','Dean\StudentRecord\StudentRecordController@view_transcript');
Route::get('college/print_curriculum_record/{idno}','Dean\StudentRecord\StudentRecordController@print_curriculum_record');
Route::get('college/true_copy_of_grades/{idno}','Dean\StudentRecord\StudentRecordController@true_copy_of_grades');
//Assessment////////////////////////////////////////////////////////////////////
Route::get('/dean/advising/{idno}','Dean\Advising\Advising@advising');
Route::get('/dean/advising/confirm_advised/{idno}/{program_code}/{level}/{curriculum_year}/{period}','Dean\Advising\Advising@confirm_advised');
Route::get('/dean/advising/print_advising_slip/{idno}','Dean\Advising\Advising@print_advising_slip');
Route::get('/college/view_grades/{idno}', 'Dean\StudentRecord\StudentRecordController@view_record');
//Ajax Assessment
Route::get('/ajax/dean/advising/get_section', 'Dean\Advising\Ajax\advising_ajax@get_section');
Route::get('/ajax/dean/advising/get_curricula', 'Dean\Advising\Ajax\advising_ajax@get_curricula');
Route::get('/ajax/dean/advising/add_to_course_offered','Dean\Advising\Ajax\advising_ajax@add_to_course_offered');
Route::get('/ajax/dean/advising/add_to_course_offered_elect','Dean\Advising\Ajax\advising_ajax@add_to_course_offered_elect');
Route::get('/ajax/dean/advising/remove_to_course_offered','Dean\Advising\Ajax\advising_ajax@remove_to_course_offered');
Route::get('/ajax/dean/advising/get_offering_per_search','Dean\Advising\Ajax\advising_ajax@get_offering_per_search');
Route::get('/ajax/dean/advising/add_all_courses','Dean\Advising\Ajax\advising_ajax@add_all_courses');
Route::get('/ajax/dean/advising/get_curriculum','Dean\Advising\Ajax\advising_ajax@get_curriculum');

//SRF
Route::get('/dean/srf','Dean\SRF\srf@index');
Route::get('/dean/srf/modify/{period}/{course_code}','Dean\SRF\srf@modify_srf');
Route::post('/dean/srf/set_srf','Dean\SRF\srf@set_srf');
Route::get('/dean/srf/student_srf','Dean\SRF\srf@student_list');
Route::get('/dean/srf/srf_balances','Dean\SRF\srf@srf_balances');
//Print SRF
Route::get('/dean/srf/print_student_list_now/{school_year}/{period}/{course_code}','Dean\SRF\srf@print_srf_list_now');
Route::get('/dean/srf/print_srf','Dean\SRF\srf@print_index');
Route::get('/dean/srf/print_srf_now/{program_code}/{level}/{period}/{curriculum_year}','Dean\SRF\srf@print_srf_now');
//Ajax SRF
Route::get('/ajax/dean/srf/get_list','Dean\SRF\Ajax\setup@get_list');
Route::get('/ajax/dean/srf/get_search_list','Dean\SRF\Ajax\setup@get_search_list');
Route::get('/ajax/dean/srf/get_courses','Dean\SRF\Ajax\setup@get_courses');
Route::get('/ajax/dean/srf/get_student_list','Dean\SRF\Ajax\setup@get_student_list');
Route::get('/ajax/dean/srf/print_get_list','Dean\SRF\Ajax\setup@print_list');
Route::get('/ajax/dean/srf/get_srf_balances','Dean\SRF\Ajax\setup@get_srf_balances');
Route::get('/dean/srf/print_srf_balances/{school_year}/{period}/{program_code}','Dean\SRF\srf@print_srf_balances');
Route::get('/ajax/dean/srf/get_subjects','Dean\SRF\Ajax\setup@get_subjects');

//Academic/DEAN/////////////////////////////////////////////////////////////////
Route::get('/academic/view_info/{idno}','Dean\Record@view_info');
Route::get('/ajax/academic/update_status','Dean\Ajax\GetStudentList_ajax@update_status');


//COLLEGE INSTRUCTOR - MAIN/////////////////////////////////////////////////////
Route::get('/college_instructor/grades/{schedule_id}', 'CollegeInstructor\Grades\GradesController@index');
Route::post('/college_instructor/grades/save_submit', 'CollegeInstructor\Grades\GradesController@save_submit');
Route::get('/college_instructor/print_list/{schedule_id}', 'CollegeInstructor\Grades\GradesController@print_list');
Route::get('/college_instructor/print_grade/{school_year}/{period}/{schedule_id}', 'CollegeInstructor\Grades\GradesController@print_grade');
Route::get('/college_instructor/export_list/{schedule_id}', 'CollegeInstructor\Grades\GradesController@export_list');

//Ajax COLLEGE INSTRUCTOR///////////////////////////////////////////////////////
Route::get('/ajax/college_instructor/grades/change_midterm/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_midterm');
Route::get('/ajax/college_instructor/grades/change_finals/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_finals');
Route::get('/ajax/college_instructor/grades/change_grade_point/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_grade_point');
Route::get('/ajax/college_instructor/grades/change_midterm_absences/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_midterm_absences');
Route::get('/ajax/college_instructor/grades/change_finals_absences/{idno}', 'CollegeInstructor\Grades\Ajax\GradesAjaxController@change_finals_absences');

//ADMIN/////////////////////////////////////////////////////////////////////////
Route::get('/admin/view_information/{idno}', 'Admin\ViewInformation\viewInfoController@index');
Route::post('/admin/resetpassword', 'Admin\ViewInformation\viewInfoController@resetpassword');
Route::post('/admin/update_info/', 'Admin\ViewInformation\viewInfoController@update_info');
Route::get('/ajax/admin/getstudentlist','Admin\Ajax\GetStudentList_ajax@getstudentlist');
Route::get('/admin/logs','Admin\Logs@view_logs');
//send sms
Route::get('/sms','Admin\SendSMS@sms');
Route::post('/send_sms','Admin\SendSMS@send_sms');

//Update college 1st year fees SY 2019
Route::get('/update_sy2019_college_fees', 'Admin\ReassessController@update_sy2019_college_fees');

//College Admission///////////////////////////////////////////////////
Route::get('/ajax/admission-hed/getstudentlist', 'AdmissionHED\Ajax\GetStudentList_ajax@getstudentlist');
//New Student
//Route::get('/admission/admission-hed/new_student','AdmissionHED\Admission\NewStudentController@index');
//Route::post('/admission/admission-hed/add_new_student','AdmissionHED\Admission\NewStudentController@add_new_student');

//BED Admission///////////////////////////////////////////////////
Route::get('/admissionbed/resend_access/{idno}', 'AdmissionBED\info@resend_access');
Route::get('/admissionbed/ajax/getstudentlist', 'AdmissionBED\Ajax\GetStudentList@index');
Route::get('/admissionbed/info/{idno}', 'AdmissionBED\info@info');
Route::get('/ajax/admissionbed/update_schedule','AdmissionBED\Ajax\GetStudentList@updateSched');
Route::get('/ajax/admissionbed/update_interview','AdmissionBED\Ajax\GetStudentList@updateSchedInterview');
Route::get('/ajax/admissionbed/update_group','AdmissionBED\Ajax\GetStudentList@updateSchedGroup');
Route::get('/ajax/admissionbed/update_individual','AdmissionBED\Ajax\GetStudentList@updateSchedIndividual');
Route::get('/ajax/admissionbed/change_applied_for','AdmissionBED\Ajax\GetStudentList@change_applied_for');
Route::get('/admissionbed/approve_application/{idno}','AdmissionBED\info@approve_application');
Route::get('/admissionbeds/disapprove_application/{idno}','AdmissionBED\info@disapprove_application');
Route::get('/admissionbed/change_status_application/{idno}','AdmissionBED\info@change_status_application');
//BED TESTING SCHEDULES/////////////////////////////////////////////////////////
Route::get('/admissionbed/testing_schedules', 'AdmissionBED\TestingSchedules@view');
Route::post('/admissionbed/add_testing_schedule', 'AdmissionBED\TestingSchedules@add');
Route::get('/admissionbed/edit_testing_schedule/{id}', 'AdmissionBED\TestingSchedules@edit');
Route::post('/admissionbed/edit_testing_schedule_now', 'AdmissionBED\TestingSchedules@edit_now');
Route::get('/admissionbed/view_testing_list/{id}', 'AdmissionBED\TestingSchedules@view_list');
Route::get('/admissionbed/remove_testing_list_student/{id}/{idno}', 'AdmissionBED\TestingSchedules@remove_list');
//BED INTERVIEW SCHEDULES/////////////////////////////////////////////////////////
Route::get('/admissionbed/interview_schedules', 'AdmissionBED\InterviewSchedules@view');
Route::post('/admissionbed/add_interview_schedule', 'AdmissionBED\InterviewSchedules@add');
Route::get('/admissionbed/edit_interview_schedule/{id}', 'AdmissionBED\InterviewSchedules@edit');
Route::post('/admissionbed/edit_interview_schedule_now', 'AdmissionBED\InterviewSchedules@edit_now');
Route::get('/admissionbed/view_interview_list/{id}', 'AdmissionBED\InterviewSchedules@view_list');
Route::get('/admissionbed/remove_interview_list_student/{id}/{idno}', 'AdmissionBED\InterviewSchedules@remove_list');
//BED GROUP INTERVIEW SCHEDULES/////////////////////////////////////////////////////////
Route::get('/admissionbed/group_schedules', 'AdmissionBED\GroupSchedules@view');
Route::post('/admissionbed/add_group_schedule', 'AdmissionBED\GroupSchedules@add');
Route::get('/admissionbed/edit_group_schedule/{id}', 'AdmissionBED\GroupSchedules@edit');
Route::post('/admissionbed/edit_group_schedule_now', 'AdmissionBED\GroupSchedules@edit_now');
Route::get('/admissionbed/view_group_list/{id}', 'AdmissionBED\GroupSchedules@view_list');
Route::get('/admissionbed/remove_group_list_student/{id}/{idno}', 'AdmissionBED\GroupSchedules@remove_list');
//BED INDIVUDUAL INTERVIEW SCHEDULES/////////////////////////////////////////////////////////
Route::get('/admissionbed/individual_schedules', 'AdmissionBED\IndividualSchedules@view');
Route::post('/admissionbed/add_individual_schedule', 'AdmissionBED\IndividualSchedules@add');
Route::get('/admissionbed/edit_individual_schedule/{id}', 'AdmissionBED\IndividualSchedules@edit');
Route::post('/admissionbed/edit_individual_schedule_now', 'AdmissionBED\IndividualSchedules@edit_now');
Route::get('/admissionbed/view_individual_list/{id}', 'AdmissionBED\IndividualSchedules@view_list');
Route::get('/admissionbed/remove_individual_list_student/{id}/{idno}', 'AdmissionBED\IndividualSchedules@remove_list');


//ACCOUNTING BREAKDOWN OF FEES//////////////////////////////////////////////////
Route::get('/accounting/breakdown_of_fees/{idno}', 'Accounting\BreakdownOfFees@index');

//Reservations Report
Route::get('/accounting/unused_reservations', 'Accounting\Reservations@index');
Route::get('/accounting/ajax/get_reservations', 'Accounting\Ajax\AjaxReservations@get_reservations');
Route::post('/accounting/print_reservations_pdf', 'Accounting\Reservations@print_reservationsPDF');
Route::post('/accounting/print_reservations_excel', 'Accounting\Reservations@print_reservationsEXCEL');

//Student Deposit Report
Route::get('/accounting/unused_deposits', 'Accounting\Reservations@deposits');
Route::get('/accounting/ajax/get_deposits', 'Accounting\Ajax\AjaxReservations@get_deposits');
Route::post('/accounting/print_deposits_pdf', 'Accounting\Reservations@print_depositsPDF');
Route::post('/accounting/print_deposits_excel', 'Accounting\Reservations@print_depositsEXCEL');

//ACCOUNTING SCHEDULE OF FEES///////////////////////////////////////////////////
Route::get('/accounting/schedule_of_fees', 'Accounting\ScheduleOfFees@index');
Route::post('/accounting/view_schedule_of_fees', 'Accounting\ScheduleOfFees@view');
//Set OR Number
Route::get('/accounting/set_or', 'Accounting\SetReceiptController@index');
Route::post('/accounting/update_or', 'Accounting\SetReceiptController@update_or');
//Search OR
Route::post('/search_or', 'Accounting\SetReceiptController@search_or_view_receipt');
Route::get('/accounting/search_or', 'Accounting\SetReceiptController@search_or');
Route::get('/accounting/ajax/getsearch_or', 'Accounting\Ajax\AjaxSetReceipt@getsearch_or');
Route::get('/accounting/ajax/getrange_or', 'Accounting\Ajax\AjaxSetReceipt@getrange_or');


//GUIDANCE BED - MAIN///////////////////////////////////////////////////////////
Route::get('/ajax/guidance_bed/getstudentlist', 'GuidanceBed\Ajax\GetStudentList_ajax@getstudentlist');
Route::get('/guidance_bed/promotions/{idno}','GuidanceBed\PromotionsController@index');
Route::post('/guidance_bed/update_promotions','GuidanceBed\PromotionsController@update_promotions');


//BED REGISTRAR
Route::get('/bedregistrar/ajax/add_discount_collection','BedRegistrar\Ajax\DiscountCollection@add_discount_collection');
Route::get('/bedregistrar/ajax/remove_discount_collection','BedRegistrar\Ajax\DiscountCollection@remove_discount_collection');
Route::get('/bedregistrar/ajax/pop_discount_collection','BedRegistrar\Ajax\DiscountCollection@pop_discount_collection');


//print assessment for Bed registrar
Route::get('/bedregistrar/print_assessment/{idno}','BedRegistrar\Assess@print_assessment');
Route::get('/bedregistrar/reassess_reservations/{idno}/{levels_reference_id}','BedRegistrar\Assess@reassess_reservations');
//reassess college with reservation
Route::get('/registrar_college/reassess_reservations/{idno}/{levels_reference_id}/{school_year}/{period}','RegistrarCollege\Assessment\AssessmentController@reassess_reservations');

//save_reason
Route::get('/ajax/cashier/reason_reverserestore/','Cashier\Ajax\ajaxReceipt@reason_reverserestore');

//print transcipt
Route::get('/registrar_college/view_transcript/print_transcript/{idno}', 'RegistrarCollege\StudentRecord\StudentRecordController@print_now');

//reset password
Route::post('/registrar_college/resetpassword','RegistrarCollege\ViewInfo\ViewInfoController@reset_password');

//shs change strand after enrllment
Route::post('/bedregistrar/assess/change_strand','BedRegistrar\Assess@change_strand');


//Scholarship College - MAIN////////////////////////////////////////////////////
Route::get('/ajax/scholarship_college/getstudentlist', 'ScholarshipCollege\Ajax\GetStudentList_ajax@getstudentlist');
Route::get('/scholarship_college/print_scholarship/{idno}', 'ScholarshipCollege\ViewScholarship@print_scholarship');
//View Scholar
Route::get('/scholarship_college/view_scholar/{idno}', 'ScholarshipCollege\ViewScholarship@index');
Route::post('/scholarship_college/update_scholar', 'ScholarshipCollege\ViewScholarship@update_now');
Route::get('/scholarship_college/view_schedule/{idno}', 'ScholarshipCollege\ViewScholarship@view_schedule');
//Report
Route::get('/scholarship_college/report/scholarship_report', 'ScholarshipCollege\ScholarshipReport@scholarship_report');
Route::get('/scholarship_college/report/print_list_of_scholars/{scholarship}/{school_year}/{period}', 'ScholarshipCollege\ScholarshipReport@print_scholarship_report');
Route::get('/ajax/scholarship_college/report/get_scholarship_report', 'ScholarshipCollege\Ajax\GetStudentList_ajax@get_scholarship_report');

//Accounting Reports SetUp./////////////////////////////////////////////////////
Route::get('/accounting/set_up_summary','Accounting\SetUpController@set_up_summary');
Route::post('/accounting/print_setupsummary','Accounting\SetUpController@print_set_up_summary');
Route::get('/accounting/ajax/getsetupsummary','Accounting\Ajax\AjaxSetUpController@getsetupsummary');

//Accounting Statement of Account///////////////////////////////////////////////
Route::get('/accounting/statement_of_account/bed','Accounting\StatementOfAccount@indexSOA_BED');
Route::get('/accounting/statement_of_account/print/bed/{remarks}/{due_date}/{idno}','Accounting\StatementOfAccount@printSOA_BED');
Route::post('/accounting/statement_of_account/print_all/bed/','Accounting\StatementOfAccount@printallSOA_BED');

//Accounting Add to Student Deposit
Route::get('/accounting/add_to_student_deposit/{idno}','Accounting\AddToStudentDeposit@add_to_student_deposit');
Route::get('/accounting/view_add_to_student_deposit/{reference_id}','Accounting\AddToStudentDeposit@view_add_to_student_deposit');
Route::post('/accounting/post_add_to_student_deposit','Accounting\AddToStudentDeposit@post_add_to_student_deposit');

//AJAX Accounting Statement of Account//////////////////////////////////////////
Route::get('/ajax/accounting/statement_of_account/bed/get_section','Accounting\Ajax\AjaxStatementOfAccount@get_section');
Route::get('/ajax/accounting/statement_of_account/bed/get_soa','Accounting\Ajax\AjaxStatementOfAccount@get_soa');


//ACCOUNTING EXAMINATION PERMIT
Route::get('/accounting/examination_permit_hed','Accounting\ExamPermit@index');
Route::get('/accounting/print_exam_permit/{school_year}/{period}/{exam_period}/{idno}','Accounting\ExamPermit@print_now');
Route::post('/accounting/examination_permit_hed/print_all','Accounting\ExamPermit@print_all');
Route::get('/accounting/ajax/getstudentpermit','Accounting\Ajax\AjaxExamPermit@getstudentpermit');

//Ledger Update logs
Route::get('/accounting/ledger_update_logs/{start_date?}/{end_date?}','Accounting\EditLedgerLogs@index');


//REPORTS BED ADMISSION
Route::get('/bedadmission/reports/pre_registered/{date_start}/{date_end}','AdmissionBED\reportsController@pre_registered');
Route::get('/bedadmission/reports/for_approval/{date_start}/{date_end}','AdmissionBED\reportsController@for_approval');
Route::get('/bedadmission/reports/approved/{date_start}/{date_end}','AdmissionBED\reportsController@approved');
Route::get('/bedadmission/reports/regrets/{date_start}/{date_end}','AdmissionBED\reportsController@regrets');
Route::get('/bedadmission/reports/reservations','AdmissionBED\reportsController@reservations');
Route::get('/bedadmission/reports/statistics/','AdmissionBED\reportsController@statistics');
Route::get('/bedadmission/reports/export_statistics/','AdmissionBED\reportsController@export_statistics');

//PRE-REGISTRATION SETTINGS BED ADMISSION
Route::get('/bedadmission/settings/admission_sy','AdmissionBED\PreRegistrationSettings@admission_sy');
Route::get('/ajax/bedadmission/settings/update_admission_sy','AdmissionBED\Ajax\GetStudentList@update_admission_sy');
Route::get('/bedadmission/settings/levels','AdmissionBED\PreRegistrationSettings@view');
Route::get('/bedadmission/settings/update_levels/{level}','AdmissionBED\PreRegistrationSettings@update');
Route::get('/bedadmission/settings/waive_payments','AdmissionBED\PreRegistrationSettings@view_waive_payments');
Route::get('/bedadmission/settings/update_waive_payments/{academic_type}','AdmissionBED\PreRegistrationSettings@update_waive_payments');
Route::get('/bedadmission/settings/pre_registration_email','AdmissionBED\PreRegistrationSettings@view_pre_registration_email');
Route::post('/bedadmission/settings/pre_registration_email/post','AdmissionBED\PreRegistrationSettings@view_pre_registration_email_post');
Route::get('/bedadmission/settings/application_result_email','AdmissionBED\PreRegistrationSettings@view_application_result_email');
Route::post('/bedadmission/settings/application_result_email/post','AdmissionBED\PreRegistrationSettings@view_application_result_email_post');
Route::get('/bedadmission/settings/pre_registration_payment_email','AdmissionBED\PreRegistrationSettings@view_pre_registration_payment_email');
Route::post('/bedadmission/settings/pre_registration_payment_email/post','AdmissionBED\PreRegistrationSettings@view_pre_registration_payment_email_post');
Route::post('/bedadmission/update_control_number', 'AdmissionBED\PreRegistrationSettings@update_control_number');


//PRE-REGISTRATION SETTINGS COLLEGE
Route::get('/admissions/settings/admission_sy','AdmissionHED\PreRegistrationSettings@admission_sy');
Route::get('/admissions/settings/programs','AdmissionHED\PreRegistrationSettings@view');
Route::get('/admissions/settings/update_programs/{program_code}','AdmissionHED\PreRegistrationSettings@update');

Route::get('/admissions/settings/pre_registration_email','AdmissionHED\PreRegistrationSettings@view_pre_registration_email');
Route::post('/admissions/settings/pre_registration_email/post','AdmissionHED\PreRegistrationSettings@view_pre_registration_email_post');
Route::get('/admissions/settings/application_result_email','AdmissionHED\PreRegistrationSettings@view_application_result_email');
Route::post('/admissions/settings/application_result_email/post','AdmissionHED\PreRegistrationSettings@view_application_result_email_post');

//ACCOUNTING EDIT LEDGER
Route::get('/accounting/edit_ledger/{idno}','Accounting\EditLedger@index');
Route::post('/accounting/edit_ledger_now','Accounting\EditLedger@update_ledger');
//Route::get('/accounting/remove_ledger/{idno}','Accounting\EditLedger@remove_ledger');


//Route::get('/accounting/update_or','Updater@update_reverserestore');


//ACCOUNTING GENERATE PASSCODE
Route::get('/accounting/ajax/get_passcode','Accounting\Ajax\AjaxGeneratePasscode@generatePasscode');


//Upload User Image
Route::get('/upload_user_image/{idno}','Uploader\ImageUpload@view_upload');
Route::post('/save_image','Uploader\ImageUpload@save_upload');
Route::get('/remove_image/{idno}','Uploader\ImageUpload@remove_image');
//print college envelope
Route::get('/print_envelope/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@print_envelope');

//bed early enrollment
Route::get('/process_early_enrollment/{idno}','BedRegistrar\EarlyEnrollment@process_cutoff');

//BED Directory
Route::get('/bedregistrar/directory/','BedRegistrar\DirectoryController@student_list');

//BED Promotions
Route::get('/bedregistrar/batch_promotions/','BedRegistrar\PromotionsController@batch_promotions');
Route::get('/bedregistrar/individual_promotions/','BedRegistrar\PromotionsController@individual_promotions');
Route::post('/bedregistrar/update_batch_promotions/','BedRegistrar\PromotionsController@update_promotions');
//BED Promotions AJAX
Route::get('/bedregistrar/ajax/promotions/getlist','BedRegistrar\Ajax\AjaxPromotionsController@getlist');

//BOOKSTORE UPDATING OF PRICES
Route::get('/bookstore/books_pricing','Bookstore\BooksPricing@booksFees');
Route::get('/bookstore/ajax/getGroupType','Bookstore\Ajax\AjaxBooksPricing@getGroupType');
Route::get('/bookstore/ajax/updateFees/{id}','Bookstore\Ajax\AjaxBooksPricing@updateFees');
Route::get('/bookstore/ajax/updateSaveFees','Bookstore\Ajax\AjaxBooksPricing@updateSaveFees');
Route::get('/bookstore/ajax/removeFees/{id}','Bookstore\Ajax\AjaxBooksPricing@removeFees');
Route::get('/bookstore/ajax/newFees/','Bookstore\Ajax\AjaxBooksPricing@newFees');
Route::get('/bookstore/ajax/newSaveFees','Bookstore\Ajax\AjaxBooksPricing@newSaveFees');
Route::get('/bookstore/ajax/getFees','Bookstore\Ajax\AjaxBooksPricing@getFees');

//ADMIN RE_ASSESS BED EARLY ENROLLMENT
Route::get('/admin/re_assess','Admin\ReassessController@index');
Route::get('/admin/ajax/getreassess_list','Admin\Ajax\AjaxReassessController@getreassess_list');
Route::post('/admin/re_assess_now','Admin\ReassessController@re_assess_now');

//Graduates/Alumni
Route::get('/registrar_college/graduates/tagging/{school_year}','RegistrarCollege\Graduates\GraduatesController@tagging');
Route::post('/registrar_college/save_tagging_of_graduates','RegistrarCollege\Graduates\GraduatesController@save_tagging');
Route::get('/registrar_college/graduates/batch_ranking','RegistrarCollege\Graduates\BatchRanking@view');
Route::get('/ajax/registrar_college/graduates/batch_ranking/get_students','RegistrarCollege\Graduates\Ajax\AjaxBatchRanking@get_students');
Route::get('/registrar_college/graduates/print_batch_ranking/{date}','RegistrarCollege\Graduates\Ajax\AjaxBatchRanking@get_students2');

//Apply Overpamyent
//Route::get('/apply_overpayment/{idno}','Accounting\Overpayment_1@process_overpayment');
Route::get('/apply_overpayment/{type}/{idno}','Accounting\Overpayment_1@process_overpayment');

//WIthdraw college student
Route::get('/registrar_college/withdraw_enrolled_student/{status}/{date_today}/{idno}','RegistrarCollege\ViewInfo\ViewInfoController@withdraw');

//email blast for hed early enrollment
Route::get('/email_blast','Admin\EmailBlast@email');

//College portal notifications
Route::get('/registrar_college/portal_notifications/{id?}','RegistrarCollege\Notifications\NotificationsController@portal_notifications');
Route::post('/registrar_college/portal_notifications/post','RegistrarCollege\Notifications\NotificationsController@post_notifications');
Route::get('/registrar_college/notifications/set_status','RegistrarCollege\Notifications\AjaxNotificationsController@set_status');

//BED portal notifications
Route::get('/bed_portal_notifications/{department}','BedRegistrar\Notifications\NotificationsController@portal_notifications');
Route::post('/bed_portal_notifications/post','BedRegistrar\Notifications\NotificationsController@post_notifications');
Route::get('/bed_notifications/set_status','BedRegistrar\Notifications\AjaxNotificationsController@set_status');
//DEBIT SUMMARY
Route::get('/accounting/debit_summary/{date_from}/{date_to}/{posted_by}','Accounting\DebitCreditSummary@debit_summary');
Route::get('/accounting/print_debit_summary/{date_from}/{date_to}/{posted_by}','Accounting\DebitCreditSummary@print_debit_summary');

//Search
Route::get('/search','HomeController@search');

//Print Schedule
Route::get('/print_schedule/{idno}','RegistrarCollege\Advising\AssigningOfSchedules@print_schedule');

//Benefits/siblings
Route::get('/bedregistrar/siblings','BedRegistrar\SiblingsBenefits@siblings');
Route::get('/bedregistrar/benefits','BedRegistrar\SiblingsBenefits@benefits');
Route::get('/bedregistrar/ajax/getstudentlist_siblings','BedRegistrar\Ajax\GetStudentList2@getSiblings');
Route::get('/bedregistrar/ajax/getstudentlist_benefits','BedRegistrar\Ajax\GetStudentList2@getBenefits');
Route::get('/remove_sibling/{idno}','BedRegistrar\SiblingsBenefits@remove_siblings');
Route::get('/remove_benefit/{idno}','BedRegistrar\SiblingsBenefits@remove_benefits');

//Update explanation of OR
Route::post('/update_explanation','Cashier\StudentLedger@update_explanation');
//Update explanation of DM
Route::post('/update_dm_explanation','Cashier\StudentLedger@update_dm_explanation');
//Update explanation of Added as SD
Route::post('/update_sd_explanation','Cashier\StudentLedger@update_sd_explanation');
//Update reason_reverse of OR
Route::post('/update_reason','Cashier\StudentLedger@update_reason');

//Accounting Student List
Route::get('/accounting/student_list_report','Accounting\StudentList@student_list_report');

//Accounting NSTP Report
Route::get('/accounting/nstp_report','Accounting\NstpController@index');
Route::post('/accounting/submit_nstp_report','Accounting\NstpController@get_list');

//HED TESTING SCHEDULES/////////////////////////////////////////////////////////
Route::get('/admissionhed/testing_schedules', 'AdmissionHED\TestingSchedules@view');
Route::post('/admissionhed/add_testing_schedule', 'AdmissionHED\TestingSchedules@add');
Route::get('/admissionhed/edit_testing_schedule/{id}', 'AdmissionHED\TestingSchedules@edit');
Route::post('/admissionhed/edit_testing_schedule_now', 'AdmissionHED\TestingSchedules@edit_now');
Route::get('/admissionhed/view_testing_list/{id}', 'AdmissionHED\TestingSchedules@view_list');
Route::get('/admissionhed/remove_testing_list_student/{id}/{idno}', 'AdmissionHED\TestingSchedules@remove_list');
Route::get('/ajax/admissionhed/update_schedule','AdmissionHED\Ajax\GetStudentList_ajax@updateSched');

//Schedule of Instructor
Route::get('/college_instructor/my_schedule','CollegeInstructor\MyScheduleController@index');
Route::get('/ajax/college_instructor/generateSchedule/','CollegeInstructor\AjaxMyScheduleController@getMySchedule');
Route::get('/college_instructor/print_my_schedule/{school_year}/{period}','CollegeInstructor\MyScheduleController@print_my_schedule');

//Paynamics Settings admin
Route::get('/admin/paynamics_manual_posting','Admin\PaynamicsController@manual_posting');
Route::get('/admin/paynamics_credentials','Admin\PaynamicsController@credential_index');
Route::post('/admin/update_paygate_details','Admin\PaynamicsController@credential_update');


//Online Payment in Cashier
Route::get('/cashier/online_payment/{date_from}/{date_to}','Cashier\OnlinePayment@index');
Route::get('/cashier/print/online_payment/{date_from}/{date_to}','Cashier\PrintController@print_online_payments');
Route::post('/issue_or_number','Cashier\OnlinePayment@issue_or_number_now');
Route::get('/ajax/issue_or_number','Cashier\OnlinePayment@issue_or_number');
//Paypal transactions
Route::get('/paypal_transactions/','Cashier\OnlinePayment@paypal_transactions');
Route::get('/ajax/getpaypaltransactions','Cashier\OnlinePayment@getpaypaltransactions');
//Online transactions
Route::get('/online_transactions/{date_from}/{date_to}','Cashier\OnlineTransactions@index');
Route::get('/search_online_transaction_history','Cashier\OnlineTransactions@search_transaction_history');
Route::get('/online_transaction_history/{request_id}','Cashier\OnlineTransactions@transaction_history');


Route::post('/dean/advising/update_advising_remarks','Dean\Advising\Advising@update_advising_remarks');

Route::get('/bedregistrar/hold_students','BedRegistrar\HoldStudents@view');
Route::get('/bedregistrar/hold_students/export','BedRegistrar\HoldStudents@export');
Route::get('/add_hold_grade/{idno}','BedRegistrar\HoldStudents@add');
Route::get('/remove_hold_grade/{idno}','BedRegistrar\HoldStudents@delete');
Route::get('/bedregistrar/ajax/getstudentlist_holdgrades','BedRegistrar\Ajax\GetStudentListDirectory@getHoldGrades');

//displaying of grades in student portal bed
Route::get('/bedregistrar/grade_portal_display_settings','BedRegistrar\GradeDisplayController@view');
Route::get('/bedregistrar/grade_portal_display_settings/update_levels/{level}','BedRegistrar\GradeDisplayController@updateStatus');
Route::post('/update_report_card_sy_display','BedRegistrar\GradeDisplayController@updatePeriod');

//Report Card Sequencing
Route::get('/bedregistrar/report_card_sequencing','BedRegistrar\ReportCardSequencing@index');
Route::post('/bedregistrar/report_card_sequencing/update','BedRegistrar\ReportCardSequencing@update');
Route::get('/bedregistrar/ajax/report_card_sequencing/view_subjects','BedRegistrar\ReportCardSequencing@getSubjects');

//Bulk Other Payment
Route::get('/accounting/bulk_other_payment','Accounting\BulkOtherPayment@index');
Route::get('/accounting/bulk_other_payment/get_list','Accounting\BulkOtherPayment@get_list');
Route::get('/accounting/bulk_other_payment/add_student','Accounting\BulkOtherPayment@add_student');
Route::get('/accounting/bulk_other_payment/remove_student','Accounting\BulkOtherPayment@remove_student');
Route::post('/accounting/bulk_other_payment/process','Accounting\BulkOtherPayment@process');

//SHS Honors
Route::get('/bedregistrar/shs_honor','BedRegistrar\SHSHonor@index');
Route::get('/ajax/bedregistrar/shs_honor/get_students','BedRegistrar\Ajax\AjaxSHSHonor@get_students');
Route::get('/bedregistrar/export/shs_honor/{level}/{school_year}','BedRegistrar\Ajax\AjaxSHSHonor@export');



Route::get('/ajax/get_province','AdmissionBED\Ajax\GetStudentList@getProvince');
Route::get('/ajax/get_municipality','AdmissionBED\Ajax\GetStudentList@getMunicipality');
Route::get('/ajax/get_brgy','AdmissionBED\Ajax\GetStudentList@getBarangay');


//Online payment portal instruction
Route::get('online_payment_portal_instructions','Admin\PaynamicsController@portal_instruction');
Route::post('online_payment_portal_instructions/update','Admin\PaynamicsController@update_portal_instruction');

//Class Leads
Route::get('bedregistrar/class_leads','BedRegistrar\ClassLeadsController@index');
Route::get('update_class_leads','BedRegistrar\Ajax\AjaxClassLeads@update_class_leads');
Route::get('modify_class_leads_level','BedRegistrar\Ajax\AjaxClassLeads@modify_levels');
Route::post('update_class_leads_level','BedRegistrar\ClassLeadsController@update_levels');



//Updating of Receipt
Route::get('/cashier/view_receipt/update_receipt/{reference_id}','Cashier\UpdateReceiptController@index');
Route::post('/update_receipt','Cashier\UpdateReceiptController@update');
Route::post('/update_receipt_nonstudent','Cashier\UpdateReceiptController@update_nonstudent');




