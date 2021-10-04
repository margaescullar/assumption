<?php

Route::get('/bedregistrar/records/{idno}','BedRegistrar\Records\indexController@index');
Route::get('/view_elementary_record/{idno}','BedRegistrar\Records\elemController@index');
Route::get('/view_secondary_record/{idno}','BedRegistrar\Records\secondController@index');