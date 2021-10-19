<?php

Route::get('/bedregistrar/records/{idno}','BedRegistrar\Records\indexController@index');
Route::get('/view_elementary_record/{idno}','BedRegistrar\Records\elemController@index');
Route::get('/view_secondary_record/{idno}','BedRegistrar\Records\secondController@index');

//print
Route::get('/print_secondary_record/{idno}','BedRegistrar\Records\secondController@print');

//adding of records
Route::get('/transcript_of_records/{idno}/add','BedRegistrar\Records\indexController@add');
Route::post('/transcript_of_records/{idno}/add','BedRegistrar\Records\indexController@post');

//updating of records
Route::get('/transcript_of_records/{idno}/{id}/update','BedRegistrar\Records\indexController@update');
Route::get('/transcript_of_records/{idno}/{id}/update_gwa','BedRegistrar\Records\indexController@update_gwa');
Route::post('/transcript_of_records/{idno}/update_gwa','BedRegistrar\Records\indexController@post_gwa');

//update TOR Details
Route::post('/update_tor_details_hs/','BedRegistrar\Records\indexController@update_tor_details');