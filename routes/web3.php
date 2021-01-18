<?php
//CHED Enrollment
Route::get('/registrar_college/reports/ched_enrollment_reports','RegistrarCollege\Reports\ChedEnrollmentReportsController@index');
//Route::get('/registrar_college/reports/ajax/getstudent','RegistrarCollege\Reports\Ajax\GetStudent@getstudent');
Route::post('/registrar_college/reports/ched_enrollment_reports/generate', 'RegistrarCollege\Reports\ChedEnrollmentReportsController@print_report');

//print transcript of records
Route::get('/registrar_college/view_transcript/finalize_transcript/{idno}', 'RegistrarCollege\StudentRecord\StudentRecordController@finalize_transcript');
Route::post('/registrar_college/view_transcript/print_transcript', 'RegistrarCollege\StudentRecord\StudentRecordController@print_transcript');

//List Transfer Student
Route::get('/registrar_college/reports/list_transfer_student','RegistrarCollege\Reports\ListTransferStudentController@index');
Route::post('/registrar_college/reports/print_transfer_student','RegistrarCollege\Reports\ListTransferStudentController@print_transfer_student');

//List Foreign Student
Route::get('/registrar_college/reports/list_foreign_student','RegistrarCollege\Reports\ListForeignStudentController@index');
Route::post('/registrar_college/reports/print_foreign_student','RegistrarCollege\Reports\ListForeignStudentController@print_foreign_student');

//List Audit Student
Route::get('/registrar_college/reports/list_audit_student','RegistrarCollege\Reports\ListAuditStudentController@index');
Route::post('/registrar_college/reports/print_audit_student','RegistrarCollege\Reports\ListAuditStudentController@print_audit_student');

//List Unofficially Enrolled Student    
Route::get('/registrar_college/reports/list_unofficially_enrolled', 'RegistrarCollege\Reports\ListUnofficiallyEnrolledController@index');
Route::post('/registrar_college/reports/print_unofficially_enrolled', 'RegistrarCollege\Reports\ListUnofficiallyEnrolledController@print_unofficial');

//Total Daily Enrollment Statistics
Route::get('/registrar_college/reports/total_daily_enrollment_statistics/{date_start}/{date_end}', 'RegistrarCollege\Reports\TotalDailyEnrollmentController@index');
Route::get('/registrar_college/reports/enrollment_statistics/print_total_daily_enrollment_statistics/{date_start}/{date_end}', 'RegistrarCollege\Reports\TotalDailyEnrollmentController@print_daily_enrollment');

//List Freshmen
Route::get('/registrar_college/reports/list_freshmen_student', 'RegistrarCollege\Reports\ListFreshmenController@index');
Route::get('/registrar_college/reports/ajax/getfreshmen', 'RegistrarCollege\Reports\ListFreshmenController@get_freshmen');
Route::get('/registrar_college/reports/ajax/printfreshmen/{school_year}', 'RegistrarCollege\Reports\Ajax\GetStudent@print_freshmen');

//added print button on show shed offering
Route::post('/registrar_college/curriculum_management/ajax/printshowoffering', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@print_offerings');

//NSTP Reports
Route::get('/registrar_college/reports/nstp_reports', 'RegistrarCollege\Reports\NstpReportsController@index');
Route::post('/registrar_college/reports/print_nstp_reports', 'RegistrarCollege\Reports\NstpReportsController@print_nstp');

//NSTP Graduates
Route::get('/registrar_college/reports/nstp_graduates', 'RegistrarCollege\Reports\NstpReportsController@index_graduates');
Route::post('/registrar_college/reports/print_nstp_graduates', 'RegistrarCollege\Reports\NstpReportsController@print_nstp_graduates');

Route::get('registrar_college/true_copy_of_grades/{idno}','RegistrarCollege\StudentRecord\StudentRecordController@true_copy_of_grades');

//View Course Offering per Room
Route::get('/registrar_college/curriculum_management/view_course_offering_room', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@index2');
Route::get('/ajax/registrar_college/curriculum_management/get_room', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_rooms');
Route::get('/ajax/registrar_college/curriculum_management/get_offerings_room', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_offerings_room');
Route::post('/registrar_college/curriculum_management/ajax/print_show_offerings_room', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@print_offerings_room');

//View Course Offering General
Route::get('/registrar_college/curriculum_management/view_course_offering_general', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@index3');
Route::get('/ajax/registrar_college/curriculum_management/view_course_offering_general', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@index3');
Route::get('/ajax/registrar_college/curriculum_management/get_general', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_general');
Route::post('/registrar_college/curriculum_management/print_get_general', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@print_offerings_general');

//View Course Offering Course
Route::get('/registrar_college/curriculum_management/view_course_offering_course', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@index4');
Route::get('/ajax/registrar_college/curriculum_management/get_courses', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_courses');
Route::get('/ajax/registrar_college/curriculum_management/get_offerings_per_course', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_offerings_per_course');
Route::post('/registrar_college/curriculum_management/ajax/print_show_offerings_course', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@print_offerings_course');

//View Course Offering Per Room
Route::get('/registrar_college/curriculum_management/view_course_offering_per_day', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@index5');
Route::get('/ajax/registrar_college/curriculum_management/get_offerings_per_day', 'RegistrarCollege\CurriculumManagement\Ajax\view_course_offering_ajax@get_offerings_per_day');
Route::post('/registrar_college/curriculum_management/ajax/print_show_offerings_per_day', 'RegistrarCollege\CurriculumManagement\ViewCourseOfferingController@print_offerings_per_day');

//Reset Pass
Route::post('/registrar_college/instructor/resetpassword','RegistrarCollege\Instructor\ViewInstructorsController@reset_password');

//Print new student (bed admission)
Route::get('/bedregistrar/print/new_student_list/{level}/{strand}/{section}/{school_year}/{period}/{value}','BedRegistrar\Ajax\GetStudentList@print_new_student_list');

//Total daily enrollment statisticss
Route::get('/bed_registrar/reports/total_daily_enrollment_statistics/{date_start}/{date_end}', 'BedRegistrar\TotalDailyEnrollment@index');
Route::get('/bed_registrar/reports/print_total_daily_enrollment_statistics/{date_start}/{date_end}', 'BedRegistrar\TotalDailyEnrollment@print_daily_enrollment');
//chagne idno script
//Route::get('/admin/changeid','Admin\ViewInformation\viewInfoController@changeIDNO');

// Accounting - Student List
Route::get('/accounting/student_list','Accounting\StudentList@student_list');
Route::post('/accounting/print_studentlist_pdf','Accounting\StudentList@print_studentlist_pdf');
Route::post('/accounting/print_studentlist_excel','Accounting\StudentList@print_studentlist_excel');
Route::get('/accounting/ajax/get_studentlist','Accounting\Ajax\AjaxStudentList@get_studentlist');

// Accounting - Setup List
Route::get('/accounting/set_up_list','Accounting\SetUpController@set_up_list');
Route::post('/accounting/print_setuplist_pdf','Accounting\SetUpController@print_setuplist_pdf');
Route::post('/accounting/print_setuplist_excel','Accounting\SetUpController@print_setuplist_excel');
Route::get('/accounting/ajax/getsubsidiary','Accounting\Ajax\AjaxSetUpController@getsubsidiary');
Route::get('/accounting/ajax/getsetuplist','Accounting\Ajax\AjaxSetUpController@getsetuplist');

// Accounting - Setup Summary Excel
Route::post('/accounting/print_setupsummary_excel','Accounting\SetUpController@print_set_up_summary_excel');

//Accounting SRF Report
Route::get('/accounting/student_related_fees','Accounting\StudentRelatedFeesController@view');
Route::post('/accounting/print_student_related_fees_pdf','Accounting\StudentRelatedFeesController@print_student_related_feesPDF');
Route::post('/accounting/print_student_related_fees_excel','Accounting\StudentRelatedFeesController@print_student_related_feesEXCEL');
Route::get('/accounting/ajax/getstudentrelatedfees','Accounting\Ajax\AjaxAccoReportsController@getstudentrelatedfees');

//Accounting Payment Summary
Route::get('/accounting/payment_summary','Accounting\PaymentSummary@payment_summary');
Route::post('/accounting/print_payment_summary_pdf','Accounting\PaymentSummary@print_payment_summary_pdf');
Route::post('/accounting/print_payment_summary_excel','Accounting\PaymentSummary@print_payment_summary_excel');
Route::get('/accounting/ajax/get_payment_summary','Accounting\Ajax\AjaxPaymentSummary@get_payment_summary');

//Accounting Payment Plans
Route::get('/accounting/payment_plans','Accounting\PaymentPlans@index');
Route::get('/accounting/ajax/get_paymentplans','Accounting\Ajax\AjaxPaymentPlans@getstudents');


//Disbursement
Route::get('/disbursement','Accounting\Disbursement@disbursement_index');
Route::get('/cancel_disbursement/{reference}','Accounting\Disbursement@cancelDisbursement');
Route::post('/print/disbursement_note','Accounting\Disbursement@print_summary');
Route::get('/ajax/get_disbursements_note','Accounting\Ajax\AjaxDisbursement@get_disbursements');
Route::get('/disbursement/new','Accounting\Disbursement@disbursement_create');
Route::get('/accounting/ajax/set_entries','Accounting\Ajax\AjaxDisbursement@save_entries');
Route::get('/accounting/ajax/remove_entries','Accounting\Ajax\AjaxDisbursement@remove_entries');
Route::post('/process_disbursement','Accounting\Disbursement@process');
Route::get('/view/disbursement/{reference}','Accounting\Disbursement@viewDisbursement');
Route::get('/print/check_voucher/{reference}','Accounting\Disbursement@printVoucher');
Route::get('/print/check_voucher_labels/{reference}','Accounting\Disbursement@printVoucherLabels');


//Request Form
Route::get('/bedregistrar/request_form','BedRegistrar\RequestForm@index');
Route::get('/get_requestforms','BedRegistrar\RequestForm@AjaxGetForm');
Route::post('/update_or_form_request','BedRegistrar\RequestForm@updateOR');
Route::get('/tag_as_claimed/{reference_id}','BedRegistrar\RequestForm@tag_as_claimed');
Route::post('/tag_as_for_claiming','BedRegistrar\RequestForm@tag_as_for_claiming');
Route::get('/bedregistrar/request_form/settings','BedRegistrar\RequestForm@settings');
Route::get('/bedregistrar/request_form/reports','BedRegistrar\RequestForm@reports');
Route::get('/get_form_details','BedRegistrar\RequestForm@AjaxGetFormDetails');
Route::post('/update_form_details','BedRegistrar\RequestForm@UpdateFormDetails');

//External Form
Route::get('/bedregistrar/external_form','BedRegistrar\ExternalForm@index');
Route::post('/add_external_form','BedRegistrar\ExternalForm@update');


//BED Report Card
Route::get('/bedregistrar/report_card','BedRegistrar\ReportCardController@index');
Route::get('/bedregistrar/ajax/report_card_view_list', 'BedRegistrar\Ajax\GetStudentList@report_card_view_list');
Route::get('/view_report_card/{idno}/{display_type}/{school_year}/{period?}','BedRegistrar\ReportCardController@view_report_card');

//BED Grade Summary
Route::get('/bedregistrar/grade_summary','BedRegistrar\GradeSummary@index_grade_summary');
Route::get('/bedregistrar/ajax/grade_summary_view_list', 'BedRegistrar\Ajax\GetStudentListDirectory@grade_summary_view_list');

//BED SAC Grade Summary
Route::get('/bedregistrar/sac_grade_summary','BedRegistrar\GradeSummary@index_sac');
Route::get('/bedregistrar/ajax/grade_summary_sac_view_list', 'BedRegistrar\Ajax\GetStudentListDirectory@grade_summary_sac_view_list');
Route::get('/bedregistrar/print/sac_grade_summary/{level}/{strand}/{section}/{school_year}/{period}','BedRegistrar\GradeSummary@print_now_sac');
//BED Conduct grade summary
Route::get('/bedregistrar/conduct_grade_summary','BedRegistrar\GradeSummary@index_cond');
Route::get('/bedregistrar/ajax/grade_summary_cond_view_list', 'BedRegistrar\Ajax\GetStudentListDirectory@grade_summary_cond_view_list');
Route::get('/bedregistrar/print/cond_grade_summary/{level}/{strand}/{section}/{school_year}/{period}','BedRegistrar\GradeSummary@print_now_cond');

Route::get('/view_narrative_report/{idno}/{school_year}','BedRegistrar\ReportCardController@narrative_report');
Route::get('/view_indicator_report/{idno}/{school_year}','BedRegistrar\ReportCardController@indicator_report');

//Batch Ranking of BED
Route::get('/bedregistrar/batch_ranking', 'BedRegistrar\BatchRanking@view');
Route::get('/ajax/bedregistrar/batch_ranking/get_students', 'BedRegistrar\Ajax\AjaxBatchRanking@get_students');
Route::get('/bedregistrar/export/batch_ranking/{level}/{strand}/{school_year}','BedRegistrar\Ajax\AjaxBatchRanking@get_students_excel');


Route::get('/bookstore/view_ordered_books','Bookstore\ViewOrderedBooks@student_list');
Route::get('/bookstore/ajax/view_list', 'Bookstore\Ajax\GetStudentList@view_list');
Route::get('/bookstore/print/student_list/{level}/{strand}/{section}/{school_year}/{period}','Bookstore\Ajax\GetStudentList@print_student_list');



//Persona Report
Route::get('/admissions/persona_statistics_report','AdmissionHED\Persona@statisticsReport');
Route::get('/admissions/persona_report/{school_year?}','AdmissionHED\Persona@report');



